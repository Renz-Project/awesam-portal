@extends('layouts.header')
@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
<style>
@media print {
    button {
        display: none;
    }
}
table td {
    white-space: normal !important;
    word-wrap: break-word;
}
table td:nth-child(3),
table td:nth-child(4) {
    white-space: normal !important;
    word-wrap: break-word;
}
</style>


@endsection
@section('content')
<form id='searchlocation' onsubmit="show();"   enctype="multipart/form-data">
    <div class='row mb-4'>
      <div class="col-md-2">
        <label class="form-label">Location</label>
        <select id="locationSelect" name="location" class="form-select" onchange="this.form.submit(),show();" required>
          @foreach($locations as $key => $location)
              <option value="{{ $location->id }}"
                  @if(!empty($selectedLocation))
                      {{ $selectedLocation == $location->id ? 'selected' : '' }}
                  @elseif($key == 0)
                      selected
                  @endif
              >
                  {{ $location->name }}
              </option>
          @endforeach
      </select>
    @if(empty($selectedLocation))
      <script>
          document.addEventListener('DOMContentLoaded', function () {
              const form = document.getElementById('searchlocation');
              const select = document.getElementById('locationSelect');

              if (form && select && !select.value) {
                  select.selectedIndex = 0; // Select first location
              }

              if (form) {
                  form.submit(); // Submit the form
              }
          });
      </script>
      @endif
    </div>
        <div class='col-xl-2'>
            <br>
                <button type="submit" class="btn btn-success" >
                <i class="ri-search-fill me-1"></i> Search
                </button>
        </div>
    </div>
</form> 
    @if($selectedLocation)
        <div class='row'> 
        <div class="col-lg-8">
            <div class="card">
            
                <div class="card-header">
                <h5 class="card-title mb-0">Transactions <button type="button" class="btn btn-success btn-icon waves-effect waves-light" title='New Transaction' data-bs-toggle="modal" data-bs-target="#newTransactionModal"><i class=" ri-add-box-line"></i></button></h5>
            </div>
                <div class="card-body">
                        
                    <table class=" example table table-bordered dt-responsive table-striped align-middle" style="width:100%">
                        <thead>
                            <tr>   
                                <th >Client</th>
                                <th >Dentist</th>
                                <th >Treatment</th>
                                <th >Product</th>
                                <th >Total Amount</th>
                                <th>Location</th>
                                <th>Payment</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaction_clients as $client)
                                <tr>   
                                    <td ><a href="{{ url('client/' . $client->id) }}" target="_blank"><img src="{{asset($client->avatar)}}" onerror="this.src='{{URL::asset('/images/aaa.png')}}';"  alt="" class="avatar-xs rounded-circle me-2 material-shadow"> {{ $client->last_name }}, {{ $client->first_name }}</a></td>
                                    <td > {{$client->transactions[0]->dentist}} <br>{{$client->transactions[0]->dentist_2}}<br>{{$client->transactions[0]->dentist_3}}</td>
                                    <td > 
                                    @foreach($client->transactions->where('product_id',null) as $transaction)
                                    @if($transaction->treatment){{$transaction->treatment}} = {{number_format($transaction->amount_paid,2)}}    <hr> @else{{$transaction->product->product_name}}({{$transaction->qty}}) = {{number_format($transaction->amount_paid,2)}}     <hr> @endif <br>
                                        
                                    @endforeach
                                    </td>
                                    <td > 
                                    @foreach($client->transactions->where('product_id',"!=",null) as $transaction)
                                    @if($transaction->treatment){{$transaction->treatment}} = {{number_format($transaction->amount_paid,2)}}    <hr> @else{{$transaction->product->product_name}}({{$transaction->qty}}) = {{number_format($transaction->amount_paid,2)}}     <hr> @endif <br>
                                     
                                    @endforeach
                                    
                                    </td>
                                    <td >{{number_format($client->transactions->sum('amount_paid'),2)}}</td>
                                    <td>{{$client->transactions['0']->location->name}}</td>
                                    <td>
                                        @php
                                            $totals = [
                                                'cash' => 0,
                                                'gcash' => 0,
                                                'HMO' => 0,
                                                'CC' => 0,
                                                'Debit' => 0,
                                                'Others' => 0,
                                            ];
                                        @endphp
                                    @foreach($client->transactions as $transaction)
                                            @foreach($transaction->payments as $payment)
                                                @php
                                                    $type = ($payment->payment_type);
                                                    if(array_key_exists($type, $totals)) {
                                                        $totals[$type] += $payment->amount;
                                                    }
                                                @endphp
                                            @endforeach
                                        @endforeach

                                    @foreach($totals as $type => $amount)
                                        @if($amount != 0)
                                            <p>{{ ucfirst($type) }}: ₱{{ number_format($amount, 2) }}</p>
                                        @endif
                                    @endforeach

                                    </td>
                                    <td>{{$client->transactions['0']->remarks}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Sales Report ({{date('M d, Y')}})</h5>
                </div>
                <div class="card-body">
                        <button class="btn btn-primary mb-3" onclick="printTable()">Print Report</button>
                        <table id="salesReportTable" class=" table table-bordered " >
                        <thead>
                            <tr>
                                <td colspan=2 class='text-center'>
                                    <img class="" src="{{asset('images/logo_mo.png')}}" width='200px' alt="Header Avatar">
                                </td>
                            </tr>
                            <tr>
                                <td colspan=2 class='text-center'>
                                    Sales Report
                                </td>
                            </tr>
                            <tr>
                                <td > Date: </td>
                                <td>({{date('M d, Y')}})</td>
                                
                            </tr>
                            <tr>
                                <td > Location: </td>
                                <td>
                                    @php
                                        $sele = $locations->where('id',$selectedLocation)->first();
                                    @endphp
                                    @if($sele)
                                    {{$sele->name}}
                                @endif
                            </td>
                            </tr>
                            <tr>
                                <td > Generated by: </td>
                                <td>{{auth()->user()->name}}</td>
                                    
                            </td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td > Sales</td>
                                <td  class='text-success'>  <b>₱ {{number_format($transactions->where('product_id',null)->sum('amount_paid'),2)}}</b></td>
                            
                            </tr>
                            <tr>
                                <td > Product</td>
                                <td  class='text-success'>  <b>₱ {{number_format($transactions->where('product_id','!=',null)->sum('amount_paid'),2)}}</b></td>
                        
                            </tr>
                            <tr>
                                <td > Total</td>
                                <td  class='text-success'> <i> <b>₱ {{number_format($transactions->where('product_id','!=',null)->sum('amount_paid')+$transactions->where('product_id',null)->sum('amount_paid'),2)}}</b></i></td>
                            
                            </tr>
                            @php
                                $totals = [
                                    'cash' => 0,
                                    'gcash' => 0,
                                    'HMO' => 0,
                                    'CC' => 0,
                                    'Debit' => 0,
                                    'Others' => 0,
                                ];

                                $transactions->flatMap(fn($t) => $t->payments)->each(function ($payment) use (&$totals) {
                                        $type = ($payment->payment_type);

                                        if (array_key_exists($type, $totals)) {
                                            $totals[$type] += $payment->amount;
                                        } 
                                    });
                                @endphp

                                @foreach($totals as $type => $amount)
                                    @if($amount != 0)
                                        @if($type != "cash")
                                        <tr>
                                            <td > {{ ucfirst($type) }}</td>
                                            <td class='text-danger'> <b>₱ {{ number_format($amount, 2) }}</b></td>
                                           
                                        </tr>
                                        @endif
                                    @endif
                                @endforeach

                                @php
                                $groupedExpenses = $expenses->groupBy('name');
                            @endphp
                            
                            @foreach($groupedExpenses as $name => $expensesByName)
                                @php
                                    $groupedByPaymentType = $expensesByName->groupBy('payment_type');
                                @endphp
                            
                                @foreach($groupedByPaymentType as $paymentType => $group)
                                    @php
                                        $totalAmount = $group->sum('amount');
                                        $class = $paymentType === 'cash' ? 'text-danger' : 'text-info';
                                    @endphp
                                    <tr>
                                        <td>{{ $name }}</td>
                                        <td class="{{ $class }}">
                                            <b>₱ {{ number_format($totalAmount, 2) }}</b>
                                            <small>({{ ucfirst($paymentType) }})</small>
                                        </td>
                                    
                                    </tr>
                                @endforeach
                            @endforeach
                            <tr>
                                <td > Cash On Hand</td>
                                <td > <b>₱ {{number_format($totals['cash']-$expenses->where('payment_type','cash')->sum('amount'),2)}}</b></td>
                            
                            </tr>
                        </tbody>
                        </table>
                </div>
            </div>
        </div>
        </div>
    @endif
@include('transactions.new_transaction')
@endsection
@section('js')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
  $(document).ready(function () {
    // Initialize Select2
    // $('#client').select2({
    //   placeholder: "Search client...",
    //   dropdownParent: $('#newTransactionModal')
    // });
    $('#client').select2({
        dropdownParent: $('#newTransactionModal'),
        placeholder: "Search client...",
        allowClear: true,
        ajax: {
            url: "{{ route('clients.search') }}", // 🔹 create this route
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term, // search term
                    selectedLocation: "{{ $selectedLocation ?? '' }}" // 🔹 pass from Blade
                };
            },
            processResults: function (data) {
                console.log(data);
                return {
                    results: data.map(function (item) {
                        return { id: item.id, text: item.last_name + ", " + item.first_name  };
                    })
                };
            },
            cache: true
        }
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
   {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script> --}}
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
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
    toggleSubmitButton();
    return total;
}

function updatePaymentTotal() {
    let totalPayment = 0;
    document.querySelectorAll('input[name="payment_amount[]"]').forEach(input => {
        totalPayment += parseFloat(input.value) || 0;
    });
    document.getElementById('totalPayment').textContent = '₱' + totalPayment.toFixed(2);
    toggleSubmitButton();
    return totalPayment;
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

function validatePaymentMatch() {
   let totalAmountText = document.getElementById('totalAmount').textContent;
let total = parseFloat(totalAmountText.replace(/[₱,]/g, '').trim());
 let paymentTotalText = document.getElementById('totalPayment').textContent;
let paymentTotal = parseFloat(paymentTotalText.replace(/[₱,]/g, '').trim());

    return Math.abs(total - paymentTotal) == 0; // accept small decimal tolerance
}

function toggleSubmitButton() {
const submitBtn = document.getElementById('save_transaction');
    if (validatePaymentMatch() == true) {
        submitBtn.disabled = false;
         document.getElementById('notequalmessage').textContent = '';
    } else {
        submitBtn.disabled = true;
        document.getElementById('notequalmessage').textContent = '⚠️ Total Payments must equal the Total Amount to be Paid before submitting.';
    }
}
</script>
<script>
function printTable() {
    var table = document.getElementById("salesReportTable").outerHTML;
    var newWindow = window.open("", "", "width=900,height=650");
    newWindow.document.write(`
        <html>
            <head>
                <title>Sales Report</title>
                <style>
                    table {
                        width: 100%;
                        border-collapse: collapse;
                    }
                    table, th, td {
                        border: 1px solid black;
                        padding: 8px;
                    }
                </style>
            </head>
            <body>
                ${table}
            </body>
        </html>
    `);
    newWindow.document.close();
    newWindow.print();
}
</script>
@endsection
