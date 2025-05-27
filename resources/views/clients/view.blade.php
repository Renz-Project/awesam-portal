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
                            <div class="card mt-n4 mx-n4">
                                <div class="bg-warning-subtle">
                                    <div class="card-body pb-0 px-4">
                                        <div class="row mb-3">
                                            <div class="col-md">
                                                <div class="row align-items-center g-3">
                                                    <div class="col-md-auto">
                                                        <div class="avatar-md">
                                                            <div class="avatar-title bg-white rounded-circle">
                                                                <img src="{{asset('/images/aaa.png')}}" alt="" class="avatar-xs">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md">
                                                        <div>
                                                            <h4 class="fw-bold">{{$client->last_name}}, {{$client->first_name}}</h4>
                                                            <div class="hstack gap-3 flex-wrap">
                                                                {{-- <div><i class="ri-building-line align-bottom me-1"></i> Themesbrand</div> --}}
                                                                {{-- <div class="vr"></div> --}}
                                                                <div>Date Registered : <span class="fw-medium">{{date('d M, Y',strtotime($client->created_at))}}</span></div>
                                                                <div class="vr"></div>
                                                                <div>Last Transaction : <span class="fw-medium">{{date('d M, Y',strtotime($client->updated_at))}}</span></div>
                                                                <div class="vr"></div>
                                                                {{-- <div class="badge rounded-pill bg-info fs-12">New</div>
                                                                <div class="badge rounded-pill bg-danger fs-12">High</div> --}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- <div class="col-md-auto">
                                                <div class="hstack gap-1 flex-wrap">
                                                    <button type="button" class="btn py-0 fs-16 favourite-btn material-shadow-none active">
                                                        <i class="ri-star-fill"></i>
                                                    </button>
                                                    <button type="button" class="btn py-0 fs-16 text-body material-shadow-none">
                                                        <i class="ri-share-line"></i>
                                                    </button>
                                                    <button type="button" class="btn py-0 fs-16 text-body material-shadow-none">
                                                        <i class="ri-flag-line"></i>
                                                    </button>
                                                </div>
                                            </div> --}}
                                        </div>

                                        <ul class="nav nav-tabs-custom border-bottom-0" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active fw-semibold" data-bs-toggle="tab" href="#project-overview" role="tab">
                                                    Personal Information
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link fw-semibold" data-bs-toggle="tab" href="#project-documents" role="tab">
                                                    Documents / Files
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link fw-semibold" data-bs-toggle="tab" href="#project-activities" role="tab">
                                                    Transactions
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <!-- end card body -->
                                </div>
                            </div>
                            <!-- end card -->
                        </div>
                        <!-- end col -->
                    </div>
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