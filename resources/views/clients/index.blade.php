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
                <h5 class="card-title mb-0">Clients <button type="button" class="btn btn-success btn-icon waves-effect waves-light" title='New Client' data-bs-toggle="modal" data-bs-target="#newClient"><i class=" ri-add-box-line"></i></button></h5>
            </div>
            <div class="card-body">
                <table id="example" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                    <thead>
                        <tr>
                          
                            <th >Name</th>
                            <th >Email</th>
                            <th >Contact Number</th>
                            <th >Last Transaction</th>
                            <th >Locations</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clients as $client)
                        <tr>
                            <td > <a href="{{ url('client/' . $client->id) }}"><img src="assets/images/users/avatar-1.jpg" alt="" class="avatar-xs rounded-circle me-2 material-shadow"> {{ $client->last_name }}, {{ $client->first_name }}</a></td>
                            <td >{{$client->email}}</td>
                            <td >{{$client->contact_number}}</td>
                            <td >{{date('d M, Y',strtotime($client->updated_at))}}</td>
                            <td>@foreach($client->locations as $location)
                                 {{$location->name}} <br>
                                @endforeach
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div><!--end col-->
</div><!--end row-->
@include('clients.new_client')
@endsection
@section('js')
 <!-- gridjs js -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>

    <script src="{{asset('inside_css/assets/js/pages/datatables.init.js')}}"></script>
    <!-- App js -->
     <script src="{{asset('inside_css/assets/libs/prismjs/prism.js')}}"></script>
@endsection