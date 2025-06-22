<?php

namespace App\Http\Controllers;
use App\OfficeSupply;
use App\OfficeCategory;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;

class OfficeSupplyController extends Controller
{
    //
    public function index()
    {
        $OfficeSupplies = OfficeSupply::all();
        $categories = OfficeCategory::all();
        return view('office_supplies.index', array(
            'products' => $OfficeSupplies, 
            'categories' => $categories));
    }
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'product_name' => 'required',
            'unit_price' => 'required|numeric|min:0',
            'ideal_stock' => 'required|integer|min:0',
        ]);
    
        $category = OfficeCategory::find($request->category_id);
    
        // Get the latest product for this category
        $lastProduct = OfficeSupply::where('category_id', $category->id)
            ->orderBy('id', 'desc')
            ->first();
    
        // Extract the last 5 digits from the product_code, if exists
        $lastNumber = 0;
        if ($lastProduct && preg_match('/\-(\d{4})$/', $lastProduct->product_code, $matches)) {
            $lastNumber = (int)$matches[1];
        }
        $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        $generatedCode = $category->code . '-' . $nextNumber;
    
        $product = new OfficeSupply();
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

        $product = OfficeSupply::findOrFail($id);
        $product->product_name = $request->product_name;
        $product->unit_price = $request->unit_price;
        $product->ideal_stock = $request->ideal_stock;
        $product->save();
        Alert::success('Product updated successfully!')->persistent('Dismiss');
        return back();
    }
}
