@extends('layouts.header')
@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">

@endsection
@section('content')
{{-- <form  onsubmit="show();"   enctype="multipart/form-data">
    <div class='row mb-4'>
        <div class="col-md-2">
            <label class="form-label">Date From</label>
            <input type='date' name='date_from' class='form-control' value='{{$date_from}}'  required>
        </div>
           <div class="col-md-2">
            <label class="form-label">Date To</label>
            <input type='date' name='date_to' class='form-control' value='{{$date_to}}' required>
        </div>
        <div class='col-xl-2'>
            <br>
                <button type="submit" class="btn btn-success" >
                <i class="ri-search-fill me-1"></i> Search
                </button>
        </div>
    </div>
</form> --}}
{{-- <div class='row mb-4'>
    <div class='col-xl-12'>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newTransactionModal">
        <i class="ri-add-fill me-1"></i> New Transaction
        </button>
    </div>
</div>
<div class='row'>
    <div  class="col-xl-4">
        <input type="text" id="searchInput" placeholder="Search transaction..." class="form-control mb-3">
    </div>
</div> --}}
<div class='row'>
  
    {{-- <div  class="col-xl-3">
        <div class="card card-height-100">
            <div class="card-body">
                <div class="d-flex flex-column h-100">
                <!-- Header: Timestamp and Action Menu -->
                <div class="d-flex">
                    <div class="flex-grow-1">
                    <p class="text-muted mb-4">Transaction #{{$transaction->id}}</p>
                    </div>
                    <div class="flex-shrink-0">
                    <div class="d-flex gap-1 align-items-center">
                        
                        <div class="dropdown">
                        <button class="btn btn-link text-muted p-1 mt-n2 py-0 text-decoration-none fs-15 material-shadow-none" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            <i class="ri-more-2-fill"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="#"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>

                <!-- Content: Transaction Info -->
                <div class="d-flex mb-3">
                    <div class="flex-shrink-0 me-3">
                    <div class="avatar-sm">
                        <span class="avatar-title bg-primary-subtle rounded p-2">
                            <img src="{{asset($transaction->client->avatar)}}" onerror="this.src='{{URL::asset('/images/aaa.png')}}';" alt="" class="avatar-xs">
                        </span>
                    </div>
                    </div>
                    <div class="flex-grow-1">
                    <h5 class="mb-1 fs-16">{{$transaction->client->last_name}}, {{$transaction->client->first_name}}</h5>
                    <p class="text-muted mb-0">{{$transaction->treatment}}<strong></strong></p>
                    </div>
                </div>

                <!-- Middle: Amount and Status -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                    <span class="text-muted">Amount:</span>
                    <h4 class="mb-0 text-success">₱{{number_format($transaction->amount_paid)}}</h4>
                    </div>
                    <span class="badge bg-success-subtle text-success">{{$transaction->dentist}}</span>
                </div>

                <!-- Bottom: Timeline -->
                <div class="mt-auto">
                    <div class="d-flex justify-content-between">
                    <span class="text-muted"><i class="ri-bank-card-fill me-1 align-middle"></i> {{$transaction->type}}</span>
                    <span class="text-muted"><i class="ri-calendar-event-fill me-1 align-middle"></i> {{date('M d, Y',strtotime($transaction->created_at))}}</span>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div> --}}
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Transactions <button type="button" class="btn btn-success btn-icon waves-effect waves-light" title='New Transaction' data-bs-toggle="modal" data-bs-target="#newTransactionModal"><i class=" ri-add-box-line"></i></button></h5>
            </div>
            <div class="card-body">
                <table id="example" class=" example table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                    <thead>
                        <tr>   
                            <th >Client</th>
                            <th >Dentist</th>
                            <th >Product/Treatment</th>

                            <th >Transactions</th>
                            <th >Total Amount</th>
                            <th>Location</th>
                            <th>Type</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $client)
                             <tr>   
                                <td ><a href="#"><img src="{{asset($client->avatar)}}" onerror="this.src='{{URL::asset('/images/aaa.png')}}';"  alt="" class="avatar-xs rounded-circle me-2 material-shadow"> {{ $client->last_name }}, {{ $client->first_name }}</a></td>
                                <td > {{$client->transactions[0]->dentist}} <br>{{$client->transactions[0]->dentist_2}}<br>{{$client->transactions[0]->dentist_3}}</td>
                                <td > {{$client->transactions->count()}}</td>
                                <td > @foreach($client->transactions as $transaction)
                                        @if($transaction->treatment){{$transaction->treatment}} = {{number_format($transaction->amount_paid,2)}} @else{{$transaction->product->product_name}}({{$transaction->qty}}) ={{number_format($transaction->amount_paid,2)}}   @endif <br>
                                    @endforeach
                                </td>
                                <td >{{number_format($client->transactions->sum('amount_paid'),2)}}</td>
                                <td>{{$client->locations['0']->name}}</td>
                                <td>{{$client->transactions['0']->type}}</td>
                                <td>{{$client->transactions['0']->remarks}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                   
                </table>
            </div>
        </div>
    </div>
</div>
@include('transactions.new_transaction')
@endsection
@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
  $(document).ready(function () {
    // Initialize Select2
    $('#client').select2({
      placeholder: "Search client...",
      dropdownParent: $('#newTransactionModal')
    });
    $('.products').select2({
      placeholder: "Search Product...",
      dropdownParent: $('#newTransactionModal')
    });

    // Add treatment row
    $('#addTreatmentBtn').on('click', function () {
      let newItem = `
        <div class="treatment-item row g-3 align-items-end mb-2">
          <div class="col-md-6">
            <input type="text" name="treatment[]" class="form-control" placeholder="Service name" required>
          </div>
          <div class="col-md-4">
            <input type="number" name="amount[]" class="form-control" placeholder="0.00" required>
          </div>
          <div class="col-md-2">
            <button type="button" class="btn btn-danger btn-remove-treatment w-100">
              <i class="ri-delete-bin-6-line"></i>
            </button>
          </div>
        </div>`;
      $('#treatment-items').append(newItem);
    });
    // Add product row
    $('#addProductBtn').on('click', function () {
      let newItem = `
        <div class="product-item row g-3 align-items-end mb-2">
          <div class="col-md-5">
            <select name="product[]" class="products form-select product-select" required>
                 <option value="">Select product...</option>
                @foreach($products as $product)
                    <option value="{{$product->id}}" data-price="{{$product->unit_price}}">
                        {{$product->code}}-{{$product->product_name}}
                    </option>
                @endforeach
            </select>
            </div>
          <div class="col-md-2">
            <input type="number" name="quantity[]" class="form-control quantity-input" min="1" value="1" required>
         </div>
          <div class="col-md-2">
            <input type="number" name="product_amount[]" class="form-control" placeholder="0.00" required>
          </div>
          <div class="col-md-2">
            <button type="button" class="btn btn-danger btn-remove-product w-100">
              <i class="ri-delete-bin-6-line"></i>
            </button>
          </div>
        </div>`;
      $('#product-items').append(newItem);
      $('.products').select2({
      placeholder: "Search Product...",
      dropdownParent: $('#newTransactionModal')
    });
    });

    // Remove treatment row
    $(document).on('click', '.btn-remove-treatment', function () {
      $(this).closest('.treatment-item').remove();
    });
    $(document).on('click', '.btn-remove-product', function () {
      $(this).closest('.product-item').remove();
    });
  });
</script>
<script>
document.getElementById("searchInput").addEventListener("keyup", function() {
    const keyword = this.value.toLowerCase();
    const cards = document.querySelectorAll(".col-xl-3");

    cards.forEach(card => {
        const cardText = card.innerText.toLowerCase();
        if (cardText.includes(keyword)) {
            card.style.display = "block";
        } else {
            card.style.display = "none";
        }
    });
});

</script>
   {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script> --}}
   <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
   <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
   <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
   <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>

   <script src="{{asset('inside_css/assets/js/pages/datatables.init.js')}}"></script>
   <!-- App js -->
    <script src="{{asset('inside_css/assets/libs/prismjs/prism.js')}}"></script>
<script>
            $('.example').DataTable({
                ordering: false
            });
    </script>
@endsection
