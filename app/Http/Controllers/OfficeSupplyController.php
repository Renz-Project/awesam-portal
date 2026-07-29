<?php

namespace App\Http\Controllers;
use App\OfficeSupply;
use App\OfficeCategory;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class OfficeSupplyController extends Controller
{
    //
    public function index()
    {
       $OfficeSupplies = OfficeSupply::with(['idealStocks', 'category'])->get();
        $categories = OfficeCategory::all();
        $locations = auth()->user()->locations; // So you can show per-location ideal stock in the view

        return view('office_supplies.index', [
            'products' => $OfficeSupplies,
            'categories' => $categories,
            'locations' => $locations,
        ]);
    }
    public function store(Request $request)
{
    $request->validate([
        'category_id' => 'required|exists:office_categories,id',
        'product_name' => 'required|string',
        'unit_price' => 'required|numeric|min:0',
        'ideal_stock' => 'required|array', // array of ideal stock per location
        'ideal_stock.*' => 'integer|min:0',
    ]);

    $category = OfficeCategory::find($request->category_id);

    // Get the latest product for this category
    $lastProduct = OfficeSupply::withTrashed()
        ->where('category_id', $category->id)
        ->orderBy('id', 'desc')
        ->first();

    // Extract the last 4 digits from the product_code
    $lastNumber = 0;
    if ($lastProduct && preg_match('/\-(\d{4})$/', $lastProduct->product_code, $matches)) {
        $lastNumber = (int)$matches[1];
    }

    $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
    $generatedCode = $category->code . '-' . $nextNumber;

    // Create office supply
    $product = new OfficeSupply();
    $product->product_code = $generatedCode;
    $product->product_name = $request->product_name;
    $product->category_id = $category->id;
    $product->unit_price = $request->unit_price;
    $product->save();

    // Save ideal stock per location
    foreach ($request->ideal_stock as $location_id => $ideal_stock) {
        DB::table('office_supply_ideal_stocks')->insert([
            'office_supply_id' => $product->id,
            'location_id' => $location_id,
            'ideal_stock' => $ideal_stock,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    Alert::success('Office Supply added successfully!')->persistent('Dismiss');
    return back();
}

   public function editProduct(Request $request, $id)
{
    $request->validate([
        'product_name' => 'required|string',
        'unit_price' => 'required|numeric|min:0',
        'ideal_stock' => 'required|array', // per-location ideal stock
        'ideal_stock.*' => 'integer|min:0',
    ]);

    $product = OfficeSupply::findOrFail($id);
    $product->product_name = $request->product_name;
    $product->unit_price = $request->unit_price;
    $product->save();

    // Update or insert ideal stock per location
    foreach ($request->ideal_stock as $location_id => $ideal_stock) {
        DB::table('office_supply_ideal_stocks')->updateOrInsert(
            [
                'office_supply_id' => $product->id,
                'location_id' => $location_id,
            ],
            [
                'ideal_stock' => $ideal_stock,
                'updated_at' => now(),
            ]
        );
    }

    if ($request->ajax()) {
        $product->load(['category', 'idealStocks']);

        return response()->json([
            'success' => true,
            'product' => [
                'id' => $product->id,
                'product_code' => $product->product_code,
                'product_name' => $product->product_name,
                'category' => optional($product->category)->category ?? 'N/A',
                'unit_price' => number_format($product->unit_price, 2),
                'ideal_stocks' => $product->idealStocks->pluck('ideal_stock', 'location_id'),
            ],
            'message' => 'Office Supply updated successfully!',
        ]);
    }

    Alert::success('Office Supply updated successfully!')->persistent('Dismiss');
    return back();
}

    public function destroy($id)
    {
        $product = OfficeSupply::findOrFail($id);
        $product->delete();

        Alert::success('Office Supply deleted successfully!')->persistent('Dismiss');
        return back();
    }
}
