@extends('layouts.header')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
@endsection
@section('content')
@if((auth()->user()->role == "Super Admin") || (auth()->user()->role == "Admin"))
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-xl-4 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="fw-medium text-muted mb-0">Total Clients</p>
                                <h2 class="mt-4 ff-secondary fw-semibold"><span class="counter-value" data-target="{{$clients->count()}}">0</span></h2>
                                {{-- <p class="mb-0 text-muted"><span class="badge bg-light text-success mb-0"><i class="ri-arrow-up-line align-middle"></i> 16.24 % </span> vs. previous month</p> --}}
                            </div>
                            <div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-info-subtle rounded-circle fs-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users text-info material-shadow"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </div> <!-- end card-->
            </div> <!-- end col-->
        
            <div class="col-xl-4 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="fw-medium text-muted mb-0">Sales Today ({{date('d M, Y')}})</p>
                                <h2 class="mt-4 ff-secondary fw-semibold"><span class="counter-value" data-target="{{$transactions->where('date',date('Y-m-d'))->sum('amount_paid')}}">0</span></h2>
                                {{-- <p class="mb-0 text-muted"><span class="badge bg-light text-danger mb-0"><i class="ri-arrow-down-line align-middle"></i> 3.96 % </span> vs. previous month</p> --}}
                            </div>
                            <div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-info-subtle rounded-circle fs-2 material-shadow">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-activity text-info"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </div> <!-- end card-->
            </div> <!-- end col-->
        
        
            <div class="col-xl-4 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="fw-medium text-muted mb-0">Transactions Today ({{date('d M, Y')}})</p>
                                <h2 class="mt-4 ff-secondary fw-semibold"><span class="counter-value" data-target="{{$transactions_group->count()}}">0</span></h2>
                                {{-- <p class="mb-0 text-muted"><span class="badge bg-light text-success mb-0"><i class="ri-arrow-up-line align-middle"></i>0</span></p> --}}
                            </div>
                            <div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-info-subtle rounded-circle fs-2 material-shadow">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-external-link text-info"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </div> <!-- end card-->
            </div> <!-- end col-->
        </div>
        <div class="row">
            <div class="col-xl-7">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Monthly Transactions and Sales ({{date('Y')}})</h4>
                    </div><!-- end card header -->
        
                    <div class="card-body">
                        <div id="column_chart" data-colors='["--vz-danger", "--vz-primary", "--vz-success"]' class="apex-charts" dir="ltr"></div>
                    </div><!-- end card-body -->
                </div><!-- end card -->
            </div>
            <div class="col-xl-5 stretch-card">
                <div class="card stretch-card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Sales per Location ({{date('d M, Y')}})</h4>
                    </div>
                    <div class="card-body stretch-card">
                        <div class="table-responsive table-card">
                            <table class="table align-middle table-bordered table-centered table-nowrap mb-0">
                                <thead class="text-muted table-light">
                                    <tr>
                                        <th scope="col" style="width: 62;">Location</th>
                                        <th scope="col">Sales</th>
                                        <th scope="col">Transactions</th>
                                        <th scope="col">DA (Low Inventory)</th>
                                        <th scope="col">FDA (Low Inventory)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($locations as $key => $location)
                                    {{-- {{dd($location)}} --}}
                                    <tr>
                                        <td>
                                            {{($location['location_name'])}}
                                        </td>
                                        <td>{{number_format($location['totalAmountPaidDaily'],2)}}</td>
                                        <td>{{number_format($location['client_count'],2)}}</td>
                                        <td><a href='' class='text-danger' data-bs-toggle="modal" data-bs-target="#DAInventory{{$key}}">{{$lowStockReport->where('location_id',$location['location_id'])->count()}}</a></td>
                                        <td><a href='#' class='text-danger' data-bs-toggle="modal" data-bs-target="#FdoInventory{{$key}}">{{$lowStockReportFdos->where('location_id',$location['location_id'])->count()}}</a></td>
                                  
                                    </tr>
                                    @endforeach
                                    
                                </tbody><!-- end tbody -->
                            </table><!-- end table -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- end col -->
        
            <!-- end col -->
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Latest Transactions ({{date('M Y')}})</h5>
                    </div>
                    <div class="card-body">
                        <table id="example" class="example table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                            <thead>
                                <tr>
                                  
                                    <th >#Id</th>
                                    <th >Client</th>
                                    <th >Dentist</th>
                                    <th >Dentist 2</th>
                                    <th >Dentist 3</th>
                                    <th >Treatment/Product</th>
                                    <th >Qty</th>
                                    <th >Total Amount</th>
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
                                    <td>{{$transaction->dentist_2}}</td>
                                    <td>{{$transaction->dentist_3}}</td>
                                    <td>@if($transaction->treatment){{$transaction->treatment}} @else{{$transaction->product->product_name}} @endif</td>
                                    <td>{{$transaction->qty}}</td>
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
    </div><!--end col-->
</div>


@foreach($locations as $key => $location)

    @include('inventory.low_stock_modal')
@endforeach
@foreach($locations as $key => $location)

    @include('inventory.low_stock_modal_fdos')
@endforeach
@endif
@if((auth()->user()->role == "Front Desk Officer"))
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-xl-4 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="fw-medium text-muted mb-0">Total Clients</p>
                                <h2 class="mt-4 ff-secondary fw-semibold"><span class="counter-value" data-target="{{$clients->count()}}">0</span></h2>
                                {{-- <p class="mb-0 text-muted"><span class="badge bg-light text-success mb-0"><i class="ri-arrow-up-line align-middle"></i> 16.24 % </span> vs. previous month</p> --}}
                            </div>
                            <div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-info-subtle rounded-circle fs-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users text-info material-shadow"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </div> <!-- end card-->
            </div> <!-- end col-->
        
            <div class="col-xl-4 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="fw-medium text-muted mb-0">Sales Today ({{date('d M, Y')}})</p>
                                <h2 class="mt-4 ff-secondary fw-semibold"><span class="counter-value" data-target="{{$transactions->where('date',date('Y-m-d'))->sum('amount_paid')}}">0</span></h2>
                                {{-- <p class="mb-0 text-muted"><span class="badge bg-light text-danger mb-0"><i class="ri-arrow-down-line align-middle"></i> 3.96 % </span> vs. previous month</p> --}}
                            </div>
                            <div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-info-subtle rounded-circle fs-2 material-shadow">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-activity text-info"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </div> <!-- end card-->
            </div> <!-- end col-->
        
        
            <div class="col-xl-4 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="fw-medium text-muted mb-0">Transactions Today ({{date('d M, Y')}})</p>
                                <h2 class="mt-4 ff-secondary fw-semibold"><span class="counter-value" data-target="{{$transactions_group->count()}}">0</span></h2>
                                {{-- <p class="mb-0 text-muted"><span class="badge bg-light text-success mb-0"><i class="ri-arrow-up-line align-middle"></i>0</span></p> --}}
                            </div>
                            <div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-info-subtle rounded-circle fs-2 material-shadow">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-external-link text-info"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </div> <!-- end card-->
            </div> <!-- end col-->
        </div>
        <div class="row">
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Latest Transactions ({{date('M Y')}})</h5>
                    </div>
                    <div class="card-body">
                        <table id="example" class="example table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                            <thead>
                                <tr>
                                  
                                    <th >#Id</th>
                                    <th >Client</th>
                                    <th >Dentist</th>
                                    <th >Dentist 2</th>
                                    <th >Dentist 3</th>
                                    <th >Treatment/Product</th>
                                    <th >Qty</th>
                                    <th >Total Amount</th>
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
                                    <td>{{$transaction->dentist_2}}</td>
                                    <td>{{$transaction->dentist_3}}</td>
                                    <td>@if($transaction->treatment){{$transaction->treatment}} @else{{$transaction->product->product_name}} @endif</td>
                                    <td>{{$transaction->qty}}</td>
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
            <div class="col-lg-5">

                 @foreach($locations as $location)
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">{{$location['location_name']}}</h5>
                    </div>
                    <div class="card-body">
                    <div class="table-responsive">
                        <table class="example table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                            <thead class="thead-light">
                                <tr>
                                    <th>Code</th>
                                    <th>Ideal Stock</th>
                                    <th>Available Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lowStockReport->where('location_id',$location['location_id']) as $key => $row)
                                <tr class="">
                                    <td>{{ $row['product_name'] }}</td>
                                    <td>{{ $row['ideal_stock'] }}</td>
                                    <td class='text-danger'>{{ $row['available_stock'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div><!--end col-->
</div>
@endif

@endsection
@section('js')
<script src="{{asset('inside_css/assets/libs/apexcharts/apexcharts.min.js')}}"></script>



<script>
    const categories = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                        'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
  
    const series = @json($data);
  
    const options = {
      chart: {
        type: 'bar',
        height: 450,
        stacked: true
      },
      series: series,
      xaxis: {
        categories: categories
      },
      colors: [
        '#FFB3BA', // Light Red / Watermelon
        '#FFDFBA', // Peach
        '#FFFFBA', // Light Yellow
        '#BAFFC9', // Mint Green
        '#BAE1FF', // Baby Blue
        '#E0BBE4', // Lavender
        '#D5AAFF', // Soft Purple
        '#B5EAD7', // Pale Green
        '#C7CEEA', // Soft Periwinkle
        '#FDCBFF'  // Cotton Candy Pink
        ],
      plotOptions: {
        bar: {
          horizontal: false,
          columnWidth: '60%',
          endingShape: 'rounded'
        }
      },
      dataLabels: {
        enabled: false
      },
      legend: {
        position: 'top'
      },
      tooltip: {
        shared: true,
        intersect: false,
        y: {
          formatter: function (val, opts) {
            return val.toFixed(2);
          },
          title: {
            formatter: function(seriesName) {
              return seriesName + ':';
            }
          }
        },
        custom: function({ series, seriesIndex, dataPointIndex, w }) {
          let monthTotal = 0;
        //   console.log(w.globals.categoryLabels);
          let tooltipHTML = '<div class="apexcharts-tooltip-title"></div>';
          tooltipHTML += '<div class="apexcharts-tooltip-series">';
  
          w.globals.seriesNames.forEach((location, i) => {
            const value = series[i][dataPointIndex];
            monthTotal += value;
            tooltipHTML += `<div>
              <span style="color:${w.globals.colors[i]}; font-weight:bold">●</span> ${location}: <strong>${Number(value).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</strong>
            </div>`;
          });
  
          tooltipHTML += `<hr style="margin: 4px 0;">
            <div><strong>Total:</strong> <strong>${Number(monthTotal).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</strong></div>`;
          tooltipHTML += '</div>';
  
          return tooltipHTML;
        }
      },
      title: {
        text: 'Sales per Location ({{date('Y')}})',
        align: 'center'
      }
    };
  
    const chart = new ApexCharts(document.querySelector("#column_chart"), options);
    chart.render();
  </script>
   <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
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