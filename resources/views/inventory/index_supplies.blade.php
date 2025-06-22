@extends('layouts.header')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
@endsection
@section('content')
<form method="GET" >
    <div class="row mb-3">
        <div class="col-md-4">
            <select name="location" class="form-control" onchange="this.form.submit()">
                <option value="">Select Location</option>
                @foreach($locations as $location)
                    <option value="{{ $location->id }}" {{ $selectedLocation == $location->name ? 'selected' : '' }}>
                        {{ $location->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</form>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Inventories</h5>
            </div>
            <div class="card-body">
                <table id="example" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                    <thead>
                        <tr>
                        <th>Product Code</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Location</th>
                        <th>Unit Price</th>
                        <th>Ideal Stock</th>
                        <th>Available Stock</th>
                        <th>Notification</th>
                        {{-- <th>Available Stock Value</th> --}}
                        <th>Action</th>

                    </tr>
                    </thead>
                    <tbody>
                        @foreach($report as $key => $row)
                            <tr>
                                <td>{{ $row['product_code'] }}</td>
                                <td>{{ $row['product_name'] }}</td>
                                <td>{{ $row['category']->category }}</td>
                                <td>{{ $row['location'] }}</td>
                                <td>{{ number_format($row['unit_price'], 2) }}</td>
                                <td>{{ number_format($row['ideal_stock'],2) }}</td>
                                <td>{{ number_format($row['available_stock'],2) }}</td>
                                <td><span class="text-danger">{{ $row['notification'] }}</span></td>
                                {{-- <td>{{ number_format($row['available_stock_value'], 2) }}</td> --}}
                                <td>
                                    <button class="btn btn-sm btn-success"  data-bs-toggle="modal" data-bs-target="#newStack-{{$key}}">+</button>
                                    <button class="btn btn-sm btn-danger"  data-bs-toggle="modal" data-bs-target="#reduceStock-{{$key}}">−</button>
                                    @include('inventory.addstack_office')
                                    @include('inventory.reducestock_office')
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div><!--end col-->
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
