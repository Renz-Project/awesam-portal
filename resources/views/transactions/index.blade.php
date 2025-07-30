@extends('layouts.header')
@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">


@endsection
@section('content')
<form  onsubmit="show();"   enctype="multipart/form-data">
    <div class='row mb-4'>
      <div class="col-md-2">
        <label class="form-label">Location</label>
        <select name="location" class="form-control" onchange="this.form.submit()">
            <option value="">Select Location</option>
            @foreach($locations as $location)
                <option value="{{ $location->id }}" @if($selectedLocation == $location->id) selected @endif>
                    {{ $location->name }}
                </option>
            @endforeach
        </select>
    </div>
        <div class='col-xl-2'>
            <br>
                <button type="submit" class="btn btn-success" >
                <i class="ri-search-fill me-1"></i> Search
                </button>
        </div>
    </div>
</form> 
    <div class="col-lg-12">
        <div class="card">
          
            <div class="card-header">
              <h5 class="card-title mb-0">Transactions <button type="button" class="btn btn-success btn-icon waves-effect waves-light" title='New Transaction' data-bs-toggle="modal" data-bs-target="#newTransactionModal"><i class=" ri-add-box-line"></i></button></h5>
          </div>
            <div class="card-body">
                    
                <table class=" example table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                    <thead>
                        <tr>   
                            <th >Client</th>
                            <th >Dentist</th>
                            <th >Product</th>
                            <th >Treatment</th>
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
                                <td > 
                                  @foreach($client->transactions->where('product_id',null) as $transaction)
                                  @if($transaction->treatment){{$transaction->treatment}} = {{number_format($transaction->amount_paid,2)}} @else{{$transaction->product->product_name}}({{$transaction->qty}}) = {{number_format($transaction->amount_paid,2)}}   @endif <br>
                              @endforeach
                                </td>
                                <td > 
                                  @foreach($client->transactions->where('product_id',"!=",null) as $transaction)
                                  @if($transaction->treatment){{$transaction->treatment}} = {{number_format($transaction->amount_paid,2)}} @else{{$transaction->product->product_name}}({{$transaction->qty}}) = {{number_format($transaction->amount_paid,2)}}   @endif <br>
                              @endforeach
                                  
                                </td>
                                <td >{{number_format($client->transactions->sum('amount_paid'),2)}}</td>
                                <td>{{$client->transactions['0']->location->name}}</td>
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
            <button type="button" class="btn btn-outline-danger btn-remove-treatment w-100">
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
          <div class="col-md-3">
            <input type="number" name="product_amount[]" class="form-control" placeholder="0.00" required>
          </div>
          <div class="col-md-2">
            <button type="button" class="btn btn-outline-danger btn-remove-product w-100">
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
   <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
   <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
   <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
   <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

   <script src="{{asset('inside_css/assets/js/pages/datatables.init.js')}}"></script>
   <!-- App js -->
    <script src="{{asset('inside_css/assets/libs/prismjs/prism.js')}}"></script>
<script>
    $(document).ready(function() {
        $('.example').DataTable({
            ordering: false,
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'print',
                    text: 'Print'
                },
                {
                    extend: 'excelHtml5',
                    text: 'Export to Excel'
                }
            ]
        });
    });
</script>
<script>
function updateTotalAmount() {
    let total = 0;

    // Sum treatment amounts
    document.querySelectorAll('input[name="amount[]"]').forEach(input => {
        total += parseFloat(input.value) || 0;
    });

    // Sum product amounts
    document.querySelectorAll('input[name="product_amount[]"]').forEach(input => {
        total += parseFloat(input.value) || 0;
    });

    document.getElementById('totalAmount').textContent = '₱' + total.toFixed(2);
    return total;
}

function updatePaymentTotal() {
    let totalPayment = 0;
    document.querySelectorAll('input[name="payment_amount[]"]').forEach(input => {
        totalPayment += parseFloat(input.value) || 0;
    });
    document.getElementById('totalPayment').textContent = '₱' + totalPayment.toFixed(2);
    return totalPayment;
}
function validatePaymentMatch() {
    const total = updateTotalAmount();
    const paymentTotal = updatePaymentTotal();
    return Math.abs(total - paymentTotal) < 0.01; // accept small decimal tolerance
}

// Update totals on input change
document.addEventListener('input', function (e) {
    if (['amount[]', 'product_amount[]', 'payment_amount[]'].includes(e.target.name)) {
        updateTotalAmount();
        updatePaymentTotal();
    }
});

// Remove item recalculations
document.addEventListener('click', function (e) {
    if (e.target.closest('.btn-remove-treatment') || e.target.closest('.btn-remove-product') || e.target.closest('.btn-remove-payment')) {
        setTimeout(() => {
            updateTotalAmount();
            updatePaymentTotal();
        }, 100);
    }
});

// Add payment row
document.getElementById('addPaymentBtn').addEventListener('click', function () {
    const container = document.getElementById('payment-items');
    const html = `
    <div class="payment-item row g-3 align-items-end mb-2">
        <div class="col-md-5">
            <select name="payment_type[]" class="form-select" required>
                <option value="">Select type...</option>
                <option value="cash" selected>Cash</option>
                <option value="gcash">GCash</option>
                <option value="HMO">HMO</option>
                <option value="CC">CC</option>
                <option value="Debit">Debit</option>
                <option value="Others">Others</option>
            </select>
        </div>
        <div class="col-md-5">
            <input type="number" name="payment_amount[]" class="form-control" placeholder="0.00" step="0.01" min="0" required>
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-outline-danger btn-remove-payment w-100">
                <i class="ri-delete-bin-6-line"></i>
            </button>
        </div>
    </div>`;
    container.insertAdjacentHTML('beforeend', html);
});

// Final validation before submit
document.querySelector('form').addEventListener('submit', function (e) {
    if (!validatePaymentMatch()) {
        e.preventDefault();
        alert('Payment total must match the total amount to be paid.');
    }
});

// Initial calculation when modal opens
document.getElementById('newTransactionModal').addEventListener('shown.bs.modal', () => {
    updateTotalAmount();
    updatePaymentTotal();
});
</script>
<script>
document.addEventListener('click', function (e) {
    // Check if the clicked element or its parent has the class .btn-remove-payment
    const removeBtn = e.target.closest('.btn-remove-payment');
    if (removeBtn) {
        // Remove the whole .payment-item row
        const paymentRow = removeBtn.closest('.payment-item');
        if (paymentRow) {
            paymentRow.remove();
            updatePaymentTotal(); // Recalculate the total payments
        }
    }
});
</script>
@endsection
