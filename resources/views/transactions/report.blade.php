@extends('layouts.header')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
@endsection
@section('content')
<form  onsubmit="show();"   enctype="multipart/form-data">
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
</form>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Latest Transactions ({{date('M Y')}})</h5>
            </div>
            <div class="card-body">
                <table id="example" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                    <thead>
                        <tr>
                          
                            <th >#Id</th>
                            <th >Client</th>
                            <th >Dentist</th>
                            <th >Treatment</th>
                            <th >Amount</th>
                            <th>Type</th>
                            <th>Remarks</th>
                            <th>Location</th>
                            <th>Encoded by</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions->sortByDesc('id') as $transaction)
                        <tr>
                            
                            <td>#{{$transaction->id}}</td>
                            <td>{{$transaction->client->last_name}}, {{$transaction->client->first_name}}</td>
                            <td>{{$transaction->dentist}}</td>
                            <td>{{$transaction->treatment}}</td>
                            <td>{{number_format($transaction->amount_paid,2)}}</td>
                            <td>{{$transaction->type}}</td>
                            <td>{{$transaction->remarks}}</td>
                            <td>{{$transaction->location->name}}</td>
                            <td>{{$transaction->user->name}}</td>
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
