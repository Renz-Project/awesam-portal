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
        $products = Product::all();
        $categories = Category::all();
        return view('products.index', compact('products', 'categories'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'product_name' => 'required',
            'unit_price' => 'required|numeric|min:0',
            'ideal_stock' => 'required|integer|min:0',
        ]);
    
        $category = Category::find($request->category_id);
    
        // Get the latest product for this category
        $lastProduct = Product::where('category_id', $category->id)
            ->orderBy('id', 'desc')
            ->first();
    
        // Extract the last 5 digits from the product_code, if exists
        $lastNumber = 0;
        if ($lastProduct && preg_match('/\-(\d{5})$/', $lastProduct->product_code, $matches)) {
            $lastNumber = (int)$matches[1];
        }
    
        $nextNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
        $generatedCode = $category->code . '-' . $nextNumber;
    
        $product = new Product();
        $product->product_code = $generatedCode;
        $product->product_name = $request->product_name;
        $product->category_id = $category->id;
        $product->unit_price = $request->unit_price;
        $product->ideal_stock = $request->ideal_stock;
        $product->save();

        Alert::success('Product added successfully!')->persistent('Dismiss');
        return back();
    }

    public function editProduct(Request $request, $id)
    {
        $request->validate([
            'product_name' => 'required',
            'unit_price' => 'required|numeric|min:0',
            'ideal_stock' => 'required|integer|min:0',
        ]);

        $product = Product::findOrFail($id);
        $product->product_name = $request->product_name;
        $product->unit_price = $request->unit_price;
        $product->ideal_stock = $request->ideal_stock;
        $product->save();
        Alert::success('Product updated successfully!')->persistent('Dismiss');
        return back();
    }
}
