<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="light" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- @laravelPWA --}}
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="shortcut icon" href="{{url('images/aaa.png')}}">
    @yield('css')
    <!-- Layout config Js -->
    <script src="{{asset('inside_css/assets/js/layout.js')}}"></script>
    <!-- Bootstrap Css -->
    <link href="{{asset('inside_css/assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{asset('inside_css/assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{asset('inside_css/assets/css/app.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="{{asset('inside_css/assets/css/custom.min.css')}}" rel="stylesheet" type="text/css" />
 
    {{-- <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css"> --}}

  
</head>
<body>
    <div id = "loader" style="display:none;" class="loader">
    </div>
    <div id="layout-wrapper">

        <header id="page-topbar">
            <div class="layout-width">
                <div class="navbar-header">
                    <div class="d-flex">
                        <!-- LOGO -->
                        <div class="navbar-brand-box horizontal-logo">
                            <a href="{{url('/')}}" class="logo logo-dark">
                                <span class="logo-sm">
                                    <img src="{{asset('images/aaa.png')}}" alt="" height="22">
                                </span>
                                <span class="logo-lg">
                                    <img src="{{asset('images/logo_mo.png')}}" alt="" height="37">
                                </span>
                            </a>

                            <a href="{{url('/')}}" class="logo logo-light">
                                <span class="logo-sm">
                                    <img src="{{asset('images/aaa.png')}}" alt="" height="22">
                                </span>
                                <span class="logo-lg">
                                    <img src="{{asset('images/logo_mo.png')}}" alt="" height="37">
                                </span>
                            </a>
                        </div>

                        <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger material-shadow-none" id="topnav-hamburger-icon">
                            <span class="hamburger-icon">
                                <span></span>
                                <span></span>
                                <span></span>
                            </span>
                        </button>

                      
                    </div>

                    <div class="d-flex align-items-center">


                     


                    
                       


                        <div class="dropdown ms-sm-3 header-item topbar-user">
                            <button type="button" class="btn material-shadow-none" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="d-flex align-items-center">
                                    <img class="rounded-circle header-profile-user" src="{{asset('/images/aaa.png')}}" alt="Header Avatar">
                                    <span class="text-start ms-xl-2">
                                        <span class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">{{current(explode(' ',auth()->user()->name))}}</span>
                                        {{-- <span class="d-none d-xl-block ms-1 fs-12 user-name-sub-text">Founder</span> --}}
                                    </span>
                                </span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <!-- item-->
                                <h6 class="dropdown-header">Welcome {{current(explode(' ',auth()->user()->name))}}!</h6>
                                {{-- <a class="dropdown-item" href="pages-profile.html"><i class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Profile</span></a> --}}
                              <div class="dropdown-divider"></div>
                               <a class="dropdown-item" href="{{ route('logout') }}" onclick="logout(); show();"> <i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> <span class="align-middle" data-key="t-logout">Logout</span></a>
                               <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

<!-- /.modal -->
        <!-- ========== App Menu ========== -->
        <div class="app-menu navbar-menu">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <!-- Dark Logo-->
                <a href="{{url('/')}}" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{asset('images/aaa.png')}}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{asset('images/logo_mo.png')}}" alt="" height="37">
                    </span>
                </a>
                <!-- Light Logo-->
                <a href="{{url('/')}}" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{asset('images/aaa.png')}}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{asset('images/logo_mo.png')}}" alt="" height="37">
                    </span>
                </a>
                <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
                    <i class="ri-record-circle-line"></i>
                </button>
            </div>
    
        
            <div id="scrollbar">
                <div class="container-fluid">


                    <div id="two-column-menu">
                    </div>
                    <ul class="navbar-nav" id="navbar-nav">
                        <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                        @if(auth()->user()->role != "Dental Assistant")
                        <li class="nav-item">
                            <a class="nav-link menu-link" href="{{url('/')}}">
                                <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards">Dashboard</span>
                            </a>
                        </li>
                        @endif
                        @if(auth()->user()->role != "Dental Assistant")
                        <li class="nav-item">
                            <a class="nav-link menu-link" href="{{url('/transactions')}}">
                                <i class="ri-file-list-line"></i> <span data-key="t-transactions">Transactions</span>
                            </a>
                        </li>
                        @endif
                        @if(auth()->user()->role != "Front Desk Officer")
                        <li class="nav-item">
                            <a class="nav-link menu-link" href="{{url('/inventory')}}">
                                <i class="ri-list-check-2"></i> <span data-key="t-inventory">DA Inventory</span>
                            </a>
                        </li>
                        @endif
                        @if(auth()->user()->role != "Dental Assistant")
                        <li class="nav-item">
                            <a class="nav-link menu-link" href="{{url('/office-supplies/inventory')}}">
                                <i class="ri-list-check-2"></i> <span data-key="t-inventory">FDO Inventory</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link menu-link" href="{{url('/clients')}}">
                                <i class=" ri-folder-user-line"></i> <span data-key="t-clients">Clients</span>
                            </a>
                        </li>
                        @endif
                        @if(auth()->user()->role == 'Admin')
                        <li class="menu-title"><span data-key="t-menu">Admin</span></li>
                          <li class="nav-item">
                              <a class="nav-link menu-link" href="#reports" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="reports">
                                <i class="ri-dashboard-2-line"></i> <span data-key="t-reports">Reports</span>
                            </a>
                            <div class="collapse menu-dropdown" id="reports">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="{{url('transactions-report')}}" class="nav-link" data-key="t-analytics"> Transactions </a>
                                    </li>
                                  
                                </ul>
                            </div>
                        </li>
                        @endif
                         @if(auth()->user()->role == 'Super Admin')
                              <li class="menu-title"><span data-key="t-menu">Super Admin</span></li>
                         <li class="nav-item">
                            <a class="nav-link menu-link" href="{{url('/users')}}">
                                <i class="ri-account-box-line"></i> <span data-key="t-users">Users</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link menu-link" href="{{url('/locations')}}">
                                <i class="ri-map-pin-line"></i> <span data-key="t-locations">Locations</span>
                            </a>
                        </li>
                        {{-- <li class="nav-item">
                            <a class="nav-link menu-link" href="{{url('/products')}}">
                                <i class="ri-stack-line"></i> <span data-key="t-products">Products</span>
                            </a>
                        </li> --}}
                        <li class="nav-item">
                            <a class="nav-link menu-link" href="#inventories" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="inventories">
                              <i class="ri-stack-line"></i> <span data-key="t-inventories">Inventories</span>
                          </a>
                          <div class="collapse menu-dropdown" id="inventories">
                              <ul class="nav nav-sm flex-column">
                                  <li class="nav-item">
                                      <a href="{{url('products')}}" class="nav-link" data-key="t-products"> Products </a>
                                  </li>
                                
                                  <li class="nav-item">
                                      <a href="{{url('office-supplies')}}" class="nav-link" data-key="t-office-supplies"> Office Supplies </a>
                                  </li>
                                
                              </ul>
                          </div>
                      </li>
                          <li class="nav-item">
                              <a class="nav-link menu-link" href="#reports" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="reports">
                                <i class="ri-dashboard-2-line"></i> <span data-key="t-reports">Reports</span>
                            </a>
                            <div class="collapse menu-dropdown" id="reports">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="{{url('transactions-report')}}" class="nav-link" data-key="t-analytics"> Transactions </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{url('audit-trails')}}" class="nav-link" data-key="t-analytics"> Audit Logs </a>
                                    </li>
                                  
                                </ul>
                            </div>
                        </li>
                      
                       
                        @endif

                    </ul>
                </div>
                <!-- Sidebar -->
            </div>

            <div class="sidebar-background"></div>
        </div>
        <!-- Left Sidebar End -->
        <!-- Vertical Overlay-->
        <div class="vertical-overlay"></div>
        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                                    <h4 class="mb-sm-0">{{Route::current()->getName()}}</h4>
        
                                    
                                </div>
                            </div>
                        </div>
                        @yield('content')
                    </div>
                </div>
            
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-6">
                                {{date('Y')}} © Awesam Clinic
                            </div>
                            <div class="col-sm-6">
                                <div class="text-sm-end d-none d-sm-block">
                                    Design & Develop by Renz Cabato
                                </div>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
    </div>


    <!--start back-to-top-->
    <button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
        <i class="ri-arrow-up-line"></i>
    </button>
    <!--end back-to-top-->

    <!--preloader-->
    <div id="preloader">
        <div id="status">
            <div class="spinner-border text-primary avatar-sm" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>

  

    <!-- Theme Settings -->
 

    <!-- JAVASCRIPT -->
    <script src="{{asset('inside_css/assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('inside_css/assets/libs/simplebar/simplebar.min.js')}}"></script>
    <script src="{{asset('inside_css/assets/libs/node-waves/waves.min.js')}}"></script>
    <script src="{{asset('inside_css/assets/libs/feather-icons/feather.min.js')}}"></script>
    <script src="{{asset('inside_css/assets/js/pages/plugins/lord-icon-2.1.0.js')}}"></script>
    <script src="{{asset('inside_css/assets/js/plugins.js')}}"></script>

    <!-- apexcharts -->
  @yield('js')
    <!-- App js -->
    <script src="{{asset('inside_css/assets/js/app.js')}}"></script>
  
    <script>
        function logout() {
        event.preventDefault();
        document.getElementById('logout-form').submit();
    }

</script>
 @include('sweetalert::alert')
</body>
</html>
