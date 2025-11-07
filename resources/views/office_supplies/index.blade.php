@extends('layouts.header')
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
@endsection

@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Office Supplies</h5>
                <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#AddProduct">+ Add Office Supply</button>
            </div>
            <div class="card-body">
                <table id="example" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Unit Price</th>
                            @foreach($locations as $location)
                                @php
                                    // Get only the first word of the location name
                                    $shortName = explode(' ', trim($location->name))[0];
                                @endphp
                                <th title="{{ $location->name }}">{{ $shortName }}</th>
                            @endforeach
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td>{{ $product->product_code }}</td>
                            <td>{{ $product->product_name }}</td>
                            <td>{{ $product->category->category ?? 'N/A' }}</td>
                            <td>{{ number_format($product->unit_price, 2) }}</td>
                            @foreach($locations as $location)
                                @php
                                    $ideal = optional(
                                        $product->idealStocks->where('location_id', $location->id)->first()
                                    )->ideal_stock ?? 0;
                                @endphp
                                <td>{{ $ideal }}</td>
                            @endforeach
                            <td>
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editProductModal{{ $product->id }}">Edit</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ADD PRODUCT MODAL --}}
<div class="modal fade" id="AddProduct" tabindex="-1" aria-labelledby="AddProductLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ url('office-supplies/store') }}" onsubmit="show();" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">New Office Supply</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @include('office_supplies.form')

                    <hr>
                    <h6>Ideal Stock per Location</h6>
                    @foreach($locations as $location)
                    <div class="mb-2">
                        <label>{{ $location->name }}</label>
                        <input type="number" name="ideal_stock[{{ $location->id }}]" class="form-control" placeholder="Ideal stock for {{ $location->name }}" min="0">
                    </div>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Create</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- EDIT PRODUCT MODALS --}}
@foreach($products as $product)
<div class="modal fade" id="editProductModal{{ $product->id }}" tabindex="-1" role="dialog" aria-labelledby="editProductLabel{{ $product->id }}" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <form action="{{ url('/office-supplies/update/'. $product->id) }}" method="POST" onsubmit="show();" enctype="multipart/form-data">
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Edit Office Supply</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
              <div class="mb-2">
                  <label>Name</label>
                  <input type="text" name="product_name" class="form-control" value="{{ $product->product_name }}" required>
              </div>
              <div class="mb-2">
                  <label>Unit Price</label>
                  <input type="number" step="0.01" name="unit_price" class="form-control" value="{{ $product->unit_price }}" required>
              </div>

              <hr>
              <h6>Ideal Stock per Location</h6>
              @foreach($locations as $location)
              @php
                  $ideal = optional(
                      $product->idealStocks->where('location_id', $location->id)->first()
                  )->ideal_stock ?? 0;
              @endphp
              <div class="mb-2">
                  <label>{{ $location->name }}</label>
                  <input type="number" name="ideal_stock[{{ $location->id }}]" class="form-control" value="{{ $ideal }}" min="0">
              </div>
              @endforeach
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Save changes</button>
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
    </form>
  </div>
</div>
@endforeach
@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<script>
$('#example').DataTable({
    ordering: false,
    dom: 'Bfrtip',
    buttons: ['excel']
});
</script>
@endsection
