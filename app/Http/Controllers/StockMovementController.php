<?php

namespace App\Http\Controllers;
use App\Product;
use App\Location;
use App\StockMovement;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;

class StockMovementController extends Controller
{
    //
    private function stockSummary(Product $product, $locationId)
    {
        $inflow = $product->stockMovements()
            ->where('location_id', $locationId)
            ->where('type', 'inflow')
            ->sum('quantity');

        $outflow = $product->stockMovements()
            ->where('location_id', $locationId)
            ->where('type', 'outflow')
            ->sum('quantity');

        $transactionOutflow = $product->transactions()
            ->where('location_id', $locationId)
            ->sum('qty');

        $updatedStock = $inflow - $outflow - $transactionOutflow;

        $ideal = $product->idealStocks()
            ->where('location_id', $locationId)
            ->value('ideal_stock') ?? 0;

        if ($updatedStock <= 0) {
            $notificationText = "⚠ Out of Stock";
        } elseif ($updatedStock < $ideal) {
            $notificationText = "⚠ Low Stock";
        } else {
            $notificationText = "";
        }

        return [
            'new_stock' => $updatedStock,
            'new_notification' => $notificationText,
        ];
    }

    public function create()
    {
        $products = Product::all();
        $locations = Location::all();
        return view('stock_movements.create', compact('products', 'locations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'location_id' => 'required|exists:locations,id',
            'type' => 'required|in:inflow,outflow',
            'quantity' => 'required|numeric|min:1',
            'remarks' => 'nullable|string'
        ]);

        // save stock movement
        $movement = new StockMovement;
        $movement->product_id = $request->product_id;
        $movement->location_id = $request->location_id;
        $movement->type = $request->type;
        $movement->date = $request->date;
        $movement->quantity = $request->quantity;
        $movement->remarks = $request->remarks;
        $movement->user_id = auth()->user()->id;
        $movement->save();

        // ==============================
        // 🔥 RECALCULATE AVAILABLE STOCK
        // ==============================

        $product = Product::with(['stockMovements', 'idealStocks'])
            ->find($request->product_id);

        // total inflow
        $inflow = $product->stockMovements()
            ->where('location_id', $request->location_id)
            ->where('type', 'inflow')
            ->sum('quantity');

        // total outflow
        $outflow = $product->stockMovements()
            ->where('location_id', $request->location_id)
            ->where('type', 'outflow')
            ->sum('quantity');

        $updatedStock = $inflow - $outflow;

        // ===================================
        // 🔥 GENERATE LOW-STOCK NOTIFICATION
        // ===================================
        $ideal = $product->idealStocks()
            ->where('location_id', $request->location_id)
            ->value('ideal_stock') ?? 0;

        if ($updatedStock <= 0) {
            $notificationText = "⚠ Out of Stock";
        } elseif ($updatedStock < $ideal) {
            $notificationText = "⚠ Low Stock";
        } else {
            $notificationText = "";
        }

        $summary = $this->stockSummary($product, $request->location_id);

        // ===========================
        // 🔥 RETURN JSON TO AJAX
        // ===========================
       return response()->json([
            'success'        => true,
            'key'            => $request->key,
            'new_stock'      => $summary['new_stock'],
            'new_notification' => $summary['new_notification'],
            'product_id'     => $request->product_id,
            'location_id'    => $request->location_id,
        ]);
    }
    public function index(Request $request)
    {
       $selectedLocation = $request->get('location');

        $products = Product::with(['stockMovements', 'transactions', 'idealStocks'])->get();

        $locations = auth()->user()->locations;
        $locationIds = $locations->pluck('id');
        $locations = Location::whereIn('id', $locationIds)->get();

        $report = [];

        foreach ($products as $product) {
            // Filter only the selected location
            foreach ($locations->where('id', $selectedLocation) as $location) {
                // Compute inflow, outflow, and transactions
                $in = $product->stockMovements
                    ->where('location_id', $location->id)
                    ->where('type', 'inflow')
                    ->sum('quantity');

                $out = $product->stockMovements
                    ->where('location_id', $location->id)
                    ->where('type', 'outflow')
                    ->sum('quantity');

                $outTransactions = $product->transactions
                    ->where('location_id', $location->id)
                    ->sum('qty');

                $available = $in - $out - $outTransactions;

                // ✅ Get ideal stock from product_ideal_stock table
                $idealStock = optional(
                    $product->idealStocks->firstWhere('location_id', $location->id)
                )->ideal_stock ?? 0;

                $report[] = [
                    'location_id' => $location->id,
                    'transactions' => $product->transactions->where('location_id', $location->id),
                    'stockMovements' => $product->stockMovements->where('location_id', $location->id),
                    'product_id' => $product->id,
                    'product_code' => $product->product_code,
                    'product_name' => $product->product_name,
                    'category' => $product->category,
                    'unit_price' => $product->unit_price,
                    'ideal_stock' => $idealStock,
                    'location' => $location->name,
                    'available_stock' => $available,
                    'notification' => $available < $idealStock ? '⚠ Low Stock' : '',
                    'available_stock_value' => $available * $product->unit_price,
                    'total_stock_value' => $in * $product->unit_price,
                ];
            
            }}


        return view('inventory.index', compact('report', 'products', 'locations','selectedLocation'));
    }
    public function update(Request $request)
{
    // dd($request->all());
    if (auth()->user()->role !== 'Super Admin') {
        abort(403, 'Unauthorized');
    }

    $request->validate([
        'movement_id' => 'required|integer|exists:stock_movements,id',
        'type' => 'required|in:inflow,outflow',
        'quantity' => 'required|numeric|min:0',
        'remarks' => 'nullable|string',
        'key' => 'nullable',
        'location_id' => 'nullable|integer|exists:locations,id',
    ]);

    $movement = StockMovement::findOrFail($request->movement_id);
    $movement->update([
        'type' => $request->type,
        'remarks' => $request->remarks,
        'quantity' => $request->quantity,
    ]);

    $locationId = $request->location_id ?: $movement->location_id;

     $product = Product::with(['stockMovements', 'idealStocks'])
            ->find($movement->product_id);

        // total inflow
        $inflow = $product->stockMovements()
            ->where('location_id', $locationId)
            ->where('type', 'inflow')
            ->sum('quantity');

        // total outflow
        $outflow = $product->stockMovements()
            ->where('location_id', $locationId)
            ->where('type', 'outflow')
            ->sum('quantity');

        $updatedStock = $inflow - $outflow;

        // ===================================
        // 🔥 GENERATE LOW-STOCK NOTIFICATION
        // ===================================
        $ideal = $product->idealStocks()
            ->where('location_id', $locationId)
            ->value('ideal_stock') ?? 0;

        if ($updatedStock <= 0) {
            $notificationText = "⚠ Out of Stock";
        } elseif ($updatedStock < $ideal) {
            $notificationText = "⚠ Low Stock";
        } else {
            $notificationText = "";
        }

        $summary = $this->stockSummary($product, $locationId);

        // ===========================
        // 🔥 RETURN JSON TO AJAX
        // ===========================
        return response()->json([
            'success' => true,
            'key' => $request->key,
            'new_stock' => $summary['new_stock'],
            'new_notification' => $summary['new_notification'],
            'product_id' => $movement->product_id,
            'location_id' => $locationId,
        ]);
}
public function history(Request $request)
{
    $product = Product::with(['stockMovements.user', 'transactions.user', 'transactions.client'])
        ->findOrFail($request->product_id);

    $location_id = $request->location_id;

    $stockMovements = $product->stockMovements()
        ->where('location_id', $location_id)
        ->orderBy('created_at', 'desc')
        ->get();

    $transactions = $product->transactions()
        ->where('location_id', $location_id)
        ->orderBy('created_at', 'desc')
        ->get();

    $html = view('inventory.ajax_history', compact('product','stockMovements','transactions'))->render();

    return response()->json([
        'title' => "Stock Movement - {$product->product_name}",
        'html' => $html
    ]);
}
}
