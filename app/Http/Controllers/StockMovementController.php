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
            'quantity' => 'required|integer|min:1',
            'remarks' => 'nullable|string'
        ]);

        $movement = new StockMovement;
        $movement->product_id = $request->product_id;
        $movement->location_id = $request->location_id;
        $movement->type = $request->type;
        $movement->date = $request->date;
        $movement->quantity = $request->quantity;
        $movement->remarks = $request->remarks;
        $movement->user_id = auth()->user()->id;
        $movement->save();

        Alert::success('Successfully stored')->persistent('Dismiss');
        return back();
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
}
