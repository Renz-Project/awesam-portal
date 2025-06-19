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
        $products = Product::with('stockMovements')->get();
        $locations = auth()->user()->locations;
        $locationIds = $locations->pluck('id');
        $locations = Location::whereIn('id',$locationIds)->get();
        // dd($selectedLocation);
        $report = [];

        foreach ($products as $product) {
            foreach ($locations->where('id',$selectedLocation) as $location) {
                $in = $product->stockMovements->where('location_id', $location->id)->where('type', 'inflow')->sum('quantity');
                $out = $product->stockMovements->where('location_id', $location->id)->where('type', 'outflow')->sum('quantity');
                $available = $in - $out;
                $report[] = [
                    'location_id' => $location->id,
                    'product_id' => $product->id,
                    'product_code' => $product->product_code,
                    'product_name' => $product->product_name,
                    'category' => $product->category,
                    'unit_price' => $product->unit_price,
                    'ideal_stock' => $product->ideal_stock,
                    'location' => $location->name,
                    'available_stock' => $available,
                    'notification' => $available < $product->ideal_stock ? '⚠ Low Stock' : '',
                    'available_stock_value' => $available * $product->unit_price,
                    'total_stock_value' => $in * $product->unit_price,
                ];
            }
        }

        return view('inventory.index', compact('report', 'products', 'locations','selectedLocation'));
    }
}
