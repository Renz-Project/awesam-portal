<?php

namespace App\Http\Controllers;
use App\Product;
use App\Category;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
class ProductController extends Controller
{
    //
    public function index()
    {
       $products = Product::with(['category', 'idealStocks'])->get();
        $locations = auth()->user()->locations;
        $categories = Category::all();
        return view('products.index', compact('products', 'categories','locations'));
    }
    public function store(Request $request)
{
    $request->validate([
        'category_id' => 'required|exists:categories,id',
        'product_name' => 'required',
        'unit_price' => 'required|numeric|min:0',
        // ideal_stock now optional array
        'ideal_stock' => 'nullable|array',
        'ideal_stock.*' => 'nullable|integer|min:0',
    ]);

    $category = Category::find($request->category_id);

    // Get the latest product for this category
    $lastProduct = Product::where('category_id', $category->id)
        ->orderBy('id', 'desc')
        ->first();

    // Extract the last 5 digits from the product_code, if exists
    $lastNumber = 0;
    if ($lastProduct && preg_match('/\-(\d{5})$/', $lastProduct->product_code, $matches)) {
        $lastNumber = (int) $matches[1];
    }

    $nextNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
    $generatedCode = $category->code . '-' . $nextNumber;

    // Create the product
    $product = Product::create([
        'product_code' => $generatedCode,
        'product_name' => $request->product_name,
        'category_id' => $category->id,
        'unit_price' => $request->unit_price,
    ]);

    // Store ideal stock per location
    if ($request->has('ideal_stock')) {
        foreach ($request->ideal_stock as $locationId => $idealStock) {
            if ($idealStock !== null && $idealStock !== '') {
                \App\Models\ProductIdealStock::create([
                    'product_id' => $product->id,
                    'location_id' => $locationId,
                    'ideal_stock' => $idealStock,
                ]);
            }
        }
    }

    Alert::success('Product added successfully!')->persistent('Dismiss');
    return back();
}
    public function editProduct(Request $request, $id)
{
    $request->validate([
        'product_name' => 'required',
        'unit_price' => 'required|numeric|min:0',
        'ideal_stock' => 'nullable|array',
        'ideal_stock.*' => 'nullable|integer|min:0',
    ]);

    $product = Product::findOrFail($id);
    $product->update([
        'product_name' => $request->product_name,
        'unit_price' => $request->unit_price,
    ]);

    // Update ideal stock per location
    if ($request->has('ideal_stock')) {
        foreach ($request->ideal_stock as $locationId => $idealStock) {
            \App\ProductIdealStock::updateOrCreate(
                [
                    'product_id' => $product->id,
                    'location_id' => $locationId,
                ],
                [
                    'ideal_stock' => $idealStock ?? 0,
                ]
            );
        }
    }

    Alert::success('Product updated successfully!')->persistent('Dismiss');
    return back();
}

}
