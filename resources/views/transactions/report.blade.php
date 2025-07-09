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
                        <h5 class="card-title mb-0">Sales Report ({{date('M d, Y',strtotime($date_from))}} - {{date('M d, Y',strtotime($date_to))}})</h5>
                    </div>
                    <div class="card-body">
                        <table id="" class=" table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                            <thead>
                                <tr>
                                    <th colspan='11' class='text-center'><b>Treatment</b></th>
                                </tr>
                                <tr>
                                    <th >#Id</th>
                                    <th >Client</th>
                                    <th >Dentist</th>
                                    <th >Dentist 2</th>
                                    <th >Dentist 3</th>
                                    <th>Type</th>
                                    <th>Remarks</th>
                                    <th>Location</th>
                                    <th>Encoded by</th>
                                    <th >Treatment</th>
                                    <th >Total Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transactions->sortByDesc('id')->where('product_id',null) as $transaction)
                                <tr>
                                    
                                    <td>#{{$transaction->id}}</td>
                                    <td>{{$transaction->client->last_name}}, {{$transaction->client->first_name}}</td>
                                    <td>{{$transaction->dentist}}</td>
                                    <td>{{$transaction->dentist_2}}</td>
                                    <td>{{$transaction->dentist_3}}</td>
                                    <td>{{$transaction->type}}</td>
                                    <td>{{$transaction->remarks}}</td>
                                    <td>{{$transaction->location->name}}</td>
                                    <td>{{$transaction->user->name}}</td>
                                    
                                    <td>{{$transaction->treatment}}</td>
                                    <td>{{number_format($transaction->amount_paid,2)}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                         <table id="" class=" table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                            <thead>
                                <tr>
                                    <th colspan='12' class='text-center'><b>Product</b></th>
                                </tr>
                                <tr>
                                    <th >#Id</th>
                                    <th >Client</th>
                                    <th >Dentist</th>
                                    <th >Dentist 2</th>
                                    <th >Dentist 3</th>
                                    <th>Type</th>
                                    <th>Remarks</th>
                                    <th>Location</th>
                                    <th>Encoded by</th>
                                    
                                    <th >Product</th>
                                    <th >Qty</th>
                                    <th >Total Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transactions->sortByDesc('id')->where('product_id','!=',null) as $transaction)
                                <tr>
                                    
                                    <td>#{{$transaction->id}}</td>
                                    <td>{{$transaction->client->last_name}}, {{$transaction->client->first_name}}</td>
                                    <td>{{$transaction->dentist}}</td>
                                    <td>{{$transaction->dentist_2}}</td>
                                    <td>{{$transaction->dentist_3}}</td>
                                    <td>{{$transaction->type}}</td>
                                    <td>{{$transaction->remarks}}</td>
                                    <td>{{$transaction->location->name}}</td>
                                    <td>{{$transaction->user->name}}</td>
                                    
                                    <td>{{$transaction->product->product_name}}</td>
                                    <td>{{$transaction->qty}}</td>
                                    <td>{{number_format($transaction->amount_paid,2)}}</td>
                                </tr>
                                @endforeach
                                
                                <tr>
                                    <td colspan='10' class='float-right'> </td>
                                    <td > Gcash</td>
                                    <td > <b>{{number_format($transactions->where('type','gcash')->sum('amount_paid'),2)}}</b></td>
                                </tr>
                                <tr>
                                    <td colspan='10' class='float-right'> </td>
                                    <td > HMO</td>
                                    <td > <b>{{number_format($transactions->where('type','HMO')->sum('amount_paid'),2)}}</b></td>
                                </tr>
                                <tr>
                                    <td colspan='10' class='float-right'> </td>
                                    <td > CC</td>
                                    <td > <b>{{number_format($transactions->where('type','CC')->sum('amount_paid'),2)}}</b></td>
                                </tr>
                                <tr>
                                    <td colspan='10' class='float-right'> </td>
                                    <td > Others</td>
                                    <td > <b>{{number_format($transactions->where('type','Others')->sum('amount_paid'),2)}}</b></td>
                                </tr>
                                <tr>
                                    <td colspan='10' class='float-right'> </td>
                                    <td > Cash On Hand</td>
                                    <td > <b>{{number_format($transactions->where('type','cash')->sum('amount_paid'),2)}}</b></td>
                                </tr>
                                <tr>
                                    <td colspan='10' class='float-right'> </td>
                                    <td > Total Sales</td>
                                    <td >  <b>{{number_format($transactions->sum('amount_paid'),2)}}</b></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
    </div><!--end col-->
</div>
@endsection
@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
 <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
   <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
   <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
   <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>

   <script src="{{asset('inside_css/assets/js/pages/datatables.init.js')}}"></script>

{{-- <script src="{{asset('inside_css/assets/js/pages/datatables.init.js')}}"></script> --}}
<!-- App js -->
 <script src="{{asset('inside_css/assets/libs/prismjs/prism.js')}}"></script>
 <script>
         $('.example').DataTable({
            ordering: false,
            dom: 'Bfrtip',
            buttons: [
                'excel'
            ]
        });
 </script>
@endsection
