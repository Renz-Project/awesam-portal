<?php

namespace App\Http\Controllers;
use App\OfficeSupply;
use App\Location;
use App\StockMovementOffice;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;

class StockMovementOfficeController extends Controller
{
    //
    private function stockSummary(OfficeSupply $product, $locationId)
    {
        $inflow = $product->stockMovements()
            ->where('location_id', $locationId)
            ->where('type', 'inflow')
            ->sum('quantity');

        $outflow = $product->stockMovements()
            ->where('location_id', $locationId)
            ->where('type', 'outflow')
            ->sum('quantity');

        $updatedStock = $inflow - $outflow;

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
        // dd($request->all());
        $request->validate([
            'product_id' => 'required|exists:office_supplies,id',
            'location_id' => 'required|exists:locations,id',
            'type' => 'required|in:inflow,outflow',
            'quantity' => 'required|integer|min:1',
            'remarks' => 'nullable|string'
        ]);

        $movement = new StockMovementOffice;
        $movement->office_supply_id = $request->product_id;
        $movement->location_id = $request->location_id;
        $movement->type = $request->type;
        $movement->date = $request->date;
        $movement->quantity = $request->quantity;
        $movement->remarks = $request->remarks;
        $movement->user_id = auth()->user()->id;
        $movement->save();

        $product = OfficeSupply::with(['stockMovements', 'idealStocks'])
            ->findOrFail($request->product_id);

        $summary = $this->stockSummary($product, $request->location_id);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'key' => $request->key,
                'new_stock' => $summary['new_stock'],
                'new_notification' => $summary['new_notification'],
                'product_id' => $request->product_id,
                'location_id' => $request->location_id,
            ]);
        }

        Alert::success('Successfully stored')->persistent('Dismiss');
        return back();
    }
    public function index(Request $request)
    {
        $selectedLocation = $request->get('location');

$products = OfficeSupply::with(['stockMovements', 'idealStocks'])->get();

$locations = auth()->user()->locations;
$locationIds = $locations->pluck('id');
$locations = Location::whereIn('id', $locationIds)->get();

$report = [];

foreach ($products as $product) {
    foreach ($locations->where('id', $selectedLocation) as $location) {
        $in = $product->stockMovements
            ->where('location_id', $location->id)
            ->where('type', 'inflow')
            ->sum('quantity');

        $out = $product->stockMovements
            ->where('location_id', $location->id)
            ->where('type', 'outflow')
            ->sum('quantity');

        $available = $in - $out;

        // ✅ Get ideal stock from office_supply_ideal_stock per location
        $idealStock = optional(
            $product->idealStocks->where('location_id', $location->id)->first()
        )->ideal_stock ?? 0;

        $report[] = [
            'location_id' => $location->id,
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
    }
}

return view('inventory.index_supplies', compact('report', 'products', 'locations', 'selectedLocation'));

    }

    public function update(Request $request)
    {
        if (auth()->user()->role !== 'Super Admin') {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'movement_id' => 'required|integer|exists:stock_movement_offices,id',
            'type' => 'required|in:inflow,outflow',
            'quantity' => 'required|numeric|min:0',
            'remarks' => 'nullable|string',
            'key' => 'nullable',
            'location_id' => 'nullable|integer|exists:locations,id',
        ]);

        $movement = StockMovementOffice::findOrFail($request->movement_id);
        $movement->update([
            'type' => $request->type,
            'remarks' => $request->remarks,
            'quantity' => $request->quantity,
        ]);

        $locationId = $request->location_id ?: $movement->location_id;

        $product = OfficeSupply::with(['stockMovements', 'idealStocks'])
            ->findOrFail($movement->office_supply_id);

        $summary = $this->stockSummary($product, $locationId);

        return response()->json([
            'success' => true,
            'key' => $request->key,
            'new_stock' => $summary['new_stock'],
            'new_notification' => $summary['new_notification'],
            'product_id' => $movement->office_supply_id,
            'location_id' => $locationId,
        ]);
    }

    public function history(Request $request)
    {
        $product = OfficeSupply::with(['stockMovements.user'])
            ->findOrFail($request->product_id);

        $location_id = $request->location_id;

        $stockMovements = $product->stockMovements()
            ->where('location_id', $location_id)
            ->orderBy('created_at', 'desc')
            ->get();

        $html = view('inventory.ajax_history_office', compact('product', 'stockMovements'))->render();

        return response()->json([
            'title' => "Stock Movement - {$product->product_name}",
            'html' => $html
        ]);
    }
}
