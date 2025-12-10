@extends('layouts.header')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
@endsection
@section('content')

 @include('error')
 <form method="GET" >
    <div class="row mb-3">
        <div class="col-md-4">
            <label class="form-label">Location</label>
            <select name="location" class="form-control" onchange="this.form.submit()">
                <option value="">Select Location</option>
                @foreach($locations as $location)
                    <option value="{{ $location->id }}" {{ $selectedLocation == $location->id ? 'selected' : '' }}>
                        {{ $location->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Date From</label>
            <input type='date' name='date_from' class='form-control' value='{{$date_from}}'   required>
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
</form>
 <div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Expenses 
                    <button type="button" class="btn btn-success btn-icon waves-effect waves-light" title='New Expense' data-bs-toggle="modal" data-bs-target="#newExpense">
                        <i class="ri-add-box-line"></i>
                    </button>
                </h5>
            </div>
            <div class="card-body">
                <table id="" class="example table table-bordered  dt-responsive  table-striped align-middle" style="width:100%">
                    <thead>
                        <tr>
                            <th>Payee Name</th>
                            <th>Reference #</th>
                            <th>Payment <br>Type</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Attachment</th>
                            <th>Remarks</th>
                            <th>Encoded By</th>
                            <th>Location</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($expenses as $expense)
                        <tr>
                            <td>{{ $expense->name }}</td>
                            <td>{{ $expense->reference_number }}</td>
                            <td>{{ $expense->payment_type }}</td>
                            <td>{{ $expense->date }}</td>
                            <td>{{ number_format($expense->amount, 2) }}</td>
                            <td>
                                @if($expense->attachment)
                                    <a href="{{ asset('uploads/expenses/' . $expense->attachment) }}" 
                                       target="_blank" 
                                       class="btn btn-sm btn-soft-primary">
                                       View Attachment
                                    </a>
                                @endif
                                @foreach($expense->attachments as $attachment)
                                    <a href="{{ asset('uploads/expenses/' . $attachment->attachment) }}" 
                                    target="_blank" 
                                    class="btn btn-sm btn-soft-primary mb-1">
                                     {{ $attachment->file_name }}
                                    </a>
                                    <br>
                                @endforeach
                            </td>
                            <td>{{ $expense->remarks }}</td>
                            
                            <td>{{ $expense->user->name }}</td>
                            <td>{{ $expense->location->name }}</td>
                            <td>
                                <!-- Action buttons here -->
                                @if((auth()->user()->role == "Admin") || (auth()->user()->role == "Super Admin") || (auth()->user()->role == "Finance"))
                                <button class="btn btn-sm btn-soft-secondary" data-bs-toggle="modal" data-bs-target="#editExpense{{ $expense->id }}">Edit</button>
                                 <!-- Delete Button -->
                                    <form action="{{ url('delete-expense/'.$expense->id) }}" 
                                            method="POST" 
                                            onsubmit="return confirm('Are you sure you want to delete this expense?');" 
                                            class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-soft-danger">
                                            Delete
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                         <tr>
                            <th colspan='4' class='text-right'>Total:</th>
                            <td colspan='6'>{{number_format($expenses->sum('amount'),2)}}</th>
                           
                         </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div><!--end col-->
</div><!--end row-->
@foreach($expenses as $expense)
@include('expenses.edit')
@endforeach
<!-- Modal -->
<div class="modal fade" id="newExpense" tabindex="-1" aria-labelledby="newExpenseLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newExpenseLabel">New Expense</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST" action="{{ url('expenses/store') }}" onsubmit="show();" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
            <div class="mb-3">
                <label for="name" class="form-label">Payee Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="reference_number" class="form-label">Reference Number (SI/DR/Etc) <small><i>(optional)</i></small></label>
                <input type="text" class="form-control" id="reference_number" name="reference_number">
            </div>
            <div class="mb-3">
                <label for="date" class="form-label">Date</label>
                @if(auth()->user()->role == 'Finance')
                  <input type="date" class="form-control" id="date" name="date" value='{{date('Y-m-d')}}' required>
                @else
                <input type="date" class="form-control" id="date" name="date" min='{{date('Y-m-d', strtotime('-7 days'))}}' max='{{date('Y-m-d')}}' value='{{date('Y-m-d')}}' required>
                @endif
            </div>
            <div class="mb-3">
                <label for="amount" class="form-label">Amount</label>
                <input type="number" step="0.01" class="form-control" id="amount" name="amount" required>
            </div>
            <div class="mb-3">
                <label for="payment_type" class="form-label">Payment Type</label>
                <select id="paymentType" name='type' class="form-select" required>
                    <option value="">Select type...</option>
                    <option value="cash" selected>Cash</option>
                    <option value="check">Check</option>
                    <option value="gcash">Gcash</option>
                    <option value="bank_transfer">Bank Transfer</option>
                </select>
            </div>
            <div class="mb-3">
                 <label for="location" class="form-label">Location</label>
                <select id="location" name='location' class="form-select" required>
                    <option value="">Select location...</option>
                    @foreach($locations as $key => $location)
                        <option value="{{$location->id}}" @if($key == 0) selected @endif>{{$location->name}}</option>
                    @endforeach
                    
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Attachment</label>

                <div id="attachment-wrapper">
                    <div class="d-flex gap-2 mb-2">
                        <input type="file" class="form-control" name="attachment[]" required>
                    </div>
                </div>

                <button type="button" class="btn btn-sm btn-primary" id="add-attachment">+ Add Attachment</button>
            </div>
            <div class="mb-3">
                <label for="remarks" class="form-label">Remarks</label>
                <textarea class="form-control" id="remarks" name="remarks" rows="3" required></textarea>
            </div>
         
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save Expense</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
@section('js')
<script>
    document.getElementById('add-attachment').addEventListener('click', function () {
        const wrapper = document.getElementById('attachment-wrapper');
        
        let row = document.createElement('div');
        row.className = 'd-flex gap-2 mb-2';

        row.innerHTML = `
            <input type="file" class="form-control" name="attachment[]">
            <button type="button" class="btn btn-danger btn-sm remove">x</button>
        `;

        wrapper.appendChild(row);

        row.querySelector('.remove').onclick = () => row.remove();
    });
</script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
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
@endsection
