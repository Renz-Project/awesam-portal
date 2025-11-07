<div class="form-group mb-3">
    <label>Product Name</label>
    <input type="text" name="product_name" class="form-control"
           value="{{ old('product_name', $product->product_name ?? '') }}" required>
</div>

<div class="form-group mb-3">
    <label>Category</label>
    <select name="category_id" class="form-control" required>
        <option value="">Select Category</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}"
                {{ (old('category_id', $product->category_id ?? '') == $category->id) ? 'selected' : '' }}>
                {{ $category->code }} - {{ $category->category }}
            </option>
        @endforeach
    </select>
</div>

<div class="form-group mb-3">
    <label>Unit Price</label>
    <input type="number" step="0.01" name="unit_price" class="form-control"
           value="{{ old('unit_price', $product->unit_price ?? '') }}" required>
</div>

<hr>
<h6>Ideal Stock per Location</h6>
@foreach($locations as $location)
    @php
        $idealStock = 0;
        if (isset($product)) {
            $idealStock = optional(
                $product->idealStocks->firstWhere('location_id', $location->id)
            )->ideal_stock ?? 0;
        }
    @endphp
    <div class="form-group mb-2">
        <label>{{ $location->name }}</label>
        <input type="number" name="ideal_stock[{{ $location->id }}]" class="form-control"
               value="{{ old('ideal_stock.' . $location->id, $idealStock) }}" min="0">
    </div>
@endforeach
