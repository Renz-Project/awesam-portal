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
                    <option value="{{ $location->id }}" {{ $selectedLocation == $location->id ? 'selected' : '' }}>
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
                               <th>Action</th>
                        <th>Product Code</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Location</th>
                        <th>Unit Price</th>
                        <th>Ideal Stock</th>
                        <th>Available Stock</th>
                        <th>Notification</th>
                        {{-- <th>Available Stock Value</th> --}}
                     

                    </tr>
                    </thead>
                    <tbody>
                        @foreach($report as $key => $row)
                            <tr id="row-{{$key}}">
                                 <td>
                                    <button class="btn btn-sm btn-success"  data-bs-toggle="modal" data-bs-target="#newStack-{{$key}}">+</button>
                                    <button class="btn btn-sm btn-danger"  data-bs-toggle="modal" data-bs-target="#reduceStock-{{$key}}">−</button>
                                    @include('inventory.addstack_office')
                                    @include('inventory.reducestock_office')
                                </td>
                                <td>{{ $row['product_code'] }}</td>
                                <td>{{ $row['product_name'] }}</td>
                                <td>{{ $row['category']->category }}</td>
                                <td>{{ $row['location'] }}</td>
                                <td>{{ number_format($row['unit_price'], 2) }}</td>
                                <td>{{ number_format($row['ideal_stock'],2) }}</td>
                                <td id="stock-{{ $key }}">
                                    <a href="#"
                                        class="openMovementModal"
                                        data-key="{{ $key }}"
                                        data-product="{{ $row['product_id'] }}"
                                        data-location="{{ $row['location_id'] }}">
                                        {{ number_format($row['available_stock'],2) }}
                                    </a>
                                </td>
                                <td id="notif-{{$key}}"><span class="text-danger">{{ $row['notification'] }}</span></td>
                                {{-- <td>{{ number_format($row['available_stock_value'], 2) }}</td> --}}
                               
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div><!--end col-->
</div>
<div class="modal fade" id="movementModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="movementModalTitle" class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="movementModalBody">
                <div class="text-center p-5">
                    <div class="spinner-border text-success"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="editMovementModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editMovementForm">
                @csrf
                <input type="hidden" id="movement_id" name="movement_id">
                <input type="hidden" id="edit_key" name="key">
                <input type="hidden" id="edit_product_id" name="product_id">
                <input type="hidden" id="edit_location_id" name="location_id">

                <div class="modal-body">
                    <div class="mb-2">
                        <label>Type</label>
                        <select id="edit_type" name="type" class="form-control">
                            <option value="inflow">Inflow</option>
                            <option value="outflow">Outflow</option>
                        </select>
                    </div>

                    <div class="mb-2">
                        <label>Quantity</label>
                        <input type="number" id="edit_quantity" name="quantity" class="form-control">
                    </div>

                    <div class="mb-2">
                        <label>Remarks</label>
                        <textarea id="edit_remarks" name="remarks" class="form-control"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
 <script>
let currentInventoryKey = null;
let currentProductId = null;
let currentLocationId = null;

function updateInventoryStockCell(response) {
    if (response.key === null || response.key === undefined || response.key === '') {
        return;
    }

    $("#stock-" + response.key).html(`
        <a href="#"
           class="openMovementModal"
           data-key="${response.key}"
           data-product="${response.product_id}"
           data-location="${response.location_id}">
            ${parseFloat(response.new_stock).toFixed(2)}
        </a>
    `);

    $("#notif-" + response.key).html(`
        <span class="text-danger">${response.new_notification}</span>
    `);
}

function loadMovementHistory(productId, locationId) {
    $("#movementModalTitle").html("Loading...");
    $("#movementModalBody").html(`
        <div class="text-center p-5">
            <div class="spinner-border text-success"></div>
        </div>
    `);

    $.ajax({
        url: "{{ url('office-supplies/stock-history') }}",
        type: "GET",
        data: {
            product_id: productId,
            location_id: locationId
        },
        success: function (response) {
            $("#movementModalTitle").html(response.title);
            $("#movementModalBody").html(response.html);

            $(".movement-table").DataTable({
                ordering: false,
                searching: false,
                paging: false,
                info: false
            });
        }
    });
}

$(document).on("submit", ".add-stock-form, .reduce-stock-form", function (e) {
    e.preventDefault();

    let form = $(this);
    let formData = new FormData(this);
    let isReduce = form.hasClass("reduce-stock-form");

    $.ajax({
        url: form.attr("action"),
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function () {
            form.find("button[type=submit]").prop("disabled", true).text("Saving...");
        },
        success: function (response) {
            updateInventoryStockCell(response);
            form.closest(".modal").modal("hide");
            form[0].reset();

            Swal.fire({
                icon: "success",
                title: isReduce ? "Stock reduced!" : "Stock updated!",
                timer: 1500,
                showConfirmButton: false
            });
        },
        error: function (xhr) {
            Swal.fire({
                icon: "error",
                title: "Failed",
                text: xhr.responseText
            });
        },
        complete: function () {
            form.find("button[type=submit]").prop("disabled", false).text("Submit");
        }
    });
});

$(document).on("click", ".openMovementModal", function (e) {
    e.preventDefault();

    currentInventoryKey = $(this).data("key");
    currentProductId = $(this).data("product");
    currentLocationId = $(this).data("location");

    let modal = new bootstrap.Modal(document.getElementById("movementModal"));
    modal.show();

    loadMovementHistory(currentProductId, currentLocationId);
});

$(document).on("click", ".editMovementBtn", function () {
    let productId = $(this).data("product") || currentProductId;
    let locationId = $(this).data("location") || currentLocationId;

    $("#movement_id").val($(this).data("id"));
    $("#edit_key").val(currentInventoryKey);
    $("#edit_product_id").val(productId);
    $("#edit_location_id").val(locationId);
    $("#edit_type").val($(this).data("type"));
    $("#edit_quantity").val($(this).data("quantity"));
    $("#edit_remarks").val($(this).data("remarks"));

    $("#editMovementModal").modal("show");
});

$("#editMovementForm").submit(function (e) {
    e.preventDefault();

    $.ajax({
        url: "{{ url('/office-supplies/stock/update') }}",
        method: "PUT",
        data: $(this).serialize(),
        success: function (res) {
            updateInventoryStockCell(res);
            $("#editMovementModal").modal("hide");
            loadMovementHistory(res.product_id, res.location_id);
            Swal.fire({
                icon: "success",
                title: "Updated successfully",
                timer: 1200,
                showConfirmButton: false
            });
        },
        error: function (xhr) {
            Swal.fire({
                icon: "error",
                title: "Failed",
                text: xhr.responseText
            });
        }
    });
});
 </script>
@endsection
