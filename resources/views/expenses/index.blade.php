@extends('layouts.header')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
@endsection
@section('content')

 @include('error')
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
                <table id="example" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                    <thead>
                        <tr>
                            <th>Expense Name</th>
                            <th>Reference #</th>
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
                            <td>{{ $expense->date }}</td>
                            <td>{{ number_format($expense->amount, 2) }}</td>
                            <td>
                                @if($expense->attachment)
                                    <button class="btn btn-sm btn-soft-primary" data-bs-toggle="modal" data-bs-target="#viewAttachment{{ $expense->id }}">View Attachment</button>
                                @endif
                            </td>
                            <td>{{ $expense->remarks }}</td>
                            
                            <td>{{ $expense->user->name }}</td>
                            <td>{{ $expense->location->name }}</td>
                            <td>
                                <!-- Action buttons here -->
                                <button class="btn btn-sm btn-soft-secondary" data-bs-toggle="modal" data-bs-target="#editExpense{{ $expense->id }}">Edit</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
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
      <form method="POST" action="{{ url('expenses/store') }}" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
            <div class="mb-3">
                <label for="name" class="form-label">Expense Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="reference_number" class="form-label">Reference Number (SI/DR/Etc) <small><i>(optional)</i></small></label>
                <input type="text" class="form-control" id="reference_number" name="reference_number">
            </div>
            <div class="mb-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" class="form-control" id="date" name="date" max='{{date('Y-m-d')}}' value='{{date('Y-m-d')}}' readonly required>
            </div>
            <div class="mb-3">
                <label for="amount" class="form-label">Amount</label>
                <input type="number" step="0.01" class="form-control" id="amount" name="amount" required>
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
                <label for="attachment" class="form-label">Attachment</label>
                <input type="file" class="form-control" id="attachment" name="attachment" required>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>
@endsection
