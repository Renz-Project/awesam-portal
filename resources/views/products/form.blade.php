
<div class="form-group">
    <label>Product Name</label>
    <input type="text" name="product_name" class="form-control" value="{{ old('product_name', $product->product_name ?? '') }}" required>
</div>
<div class="form-group">
    <label>Category</label>
    <select name="category_id" class="form-control" required>
        <option value="">Select Category</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}">{{ $category->code }} - {{ $category->category }}</option>
        @endforeach
    </select>
</div>
<div class="form-group">
    <label>Unit Price</label>
    <input type="number" step="0.01" name="unit_price" class="form-control" value="{{ old('unit_price', $product->unit_price ?? '') }}" required>
</div>
<div class="form-group">
    <label>Ideal Stock</label>
    <input type="number" name="ideal_stock" class="form-control" value="{{ old('ideal_stock', $product->ideal_stock ?? '') }}" required>
</div>
