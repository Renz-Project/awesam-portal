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
            <div class="card-header">
                <h5 class="card-title mb-0">Products</h5>
                <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#AddProduct">+ Add Product</button>
            </div>
            <div class="card-body">
                <table id="example" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Unit Price</th>
                            <th>Ideal Stock</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
            <tr>
                <td>{{ $product->product_code }}</td>
                <td>{{ $product->product_name }}</td>
                <td>{{ $product->category->category }}</td>
                <td>{{ $product->unit_price }}</td>
                <td>{{ $product->ideal_stock }}</td>
                <td>
                    <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editProductModal{{ $product->id }}">Edit</button>
                </td>
            </tr>

            <!-- Edit Product Modal -->
            
            @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div><!--end col-->
</div>
<div class="modal fade" id="AddProduct" tabindex="-1" aria-labelledby="AddProductLabel" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newUserLabel">New Client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method='POST' action="{{ url('products/store') }}" onsubmit="show();"  enctype="multipart/form-data">
                @csrf  
            <div class="modal-body">
            
                     @include('products.form')
             
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Create</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </form>
        </div>
    </div>
</div>
</div>
@endsection
@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>

{{-- <script src="{{asset('inside_css/assets/js/pages/datatables.init.js')}}"></script> --}}
<!-- App js -->
 <script src="{{asset('inside_css/assets/libs/prismjs/prism.js')}}"></script>
 <script>
         $('#example').DataTable({
            ordering: false,
            dom: 'Bfrtip',
            buttons: [
                'excel'
            ]
        });
 </script>
@endsection
