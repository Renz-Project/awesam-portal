@extends('layouts.header')
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
                                            <a href='#'><img data-bs-toggle="modal" data-bs-target="#uploadAvatar" src="{{($client->avatar)}}" onerror="this.src='{{URL::asset('/images/aaa.png')}}';" alt="" class="avatar-xs"></a>
                                           @include('clients.change_avatar')
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
<div class="row">
    <div class="col-lg-12">
        <div class="tab-content text-muted">
            <div class="tab-pane fade show active" id="project-overview" role="tabpanel">
                <div class="row">
                    <div class="col-xl-9 col-lg-8">
                        <form method='POST' action='edit-client-information/{{$client->id}}' onsubmit="show();"  enctype="multipart/form-data">
                        @csrf   
                        <div class="card">
                            <div class="card-body">
                                <div class="text-muted">
                                    <h6 class="mb-3 fw-semibold text-uppercase">Information</h6>
                                    <div class='row mb-3'>
                                        <div class="col-xxl-4 col-md-6">
                                            <div>
                                                <label for="first_name" class="form-label">First Name</label>
                                                <input type="text" class="form-control" id="first_name" placeholder="First Name" value="{{$client->first_name}}" name="first_name" required> 
                                            </div>
                                        </div>
                                        <div class="col-xxl-4 col-md-6">
                                             <div>
                                                <label for="middle_name" class="form-label">Middle Name (optional)</label>
                                                <input type="text" class="form-control" id="middle_name" name='middle_name' value="{{$client->middle_name}}" placeholder="Middle Name" >
                                            </div>
                                        </div>
                                        <div class="col-xxl-4 col-md-6">
                                            <div>
                                                <label for="last_name" class="form-label">Last Name</label>
                                                <input type="text" class="form-control" id="last_name" name='last_name' value="{{$client->last_name}}"  placeholder="Last Name" Required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class='row mb-3'>
                                        <div class="col-xxl-2 col-md-6">
                                            <div>
                                                <label for="Nickname" class="form-label">Nickname</label>
                                                <input type="text" class="form-control" id="Nickname" placeholder="Nickname" value="{{$client->nickname}}" name="nickname" > 
                                            </div>
                                        </div>
                                        <div class="col-xxl-2 col-md-6">
                                            <div>
                                                <label for="Birthdate" class="form-label">Birthdate</label>
                                                <input type="date" class="form-control" id="Birthdate" placeholder="Birthdate" value="{{$client->birthdate}}" name="birthdate" > 
                                            </div>
                                        </div>
                                        <div class="col-xxl-2 col-md-6">
                                            <div>
                                                <label for="sex" class="form-label">Sex (M/F)</label>
                                                {{-- <input type="text" class="form-control" id="sex" placeholder="M/F" value="{{$client->sex}}" name="sex" >  --}}
                                                <select class='form-control' name='sex' >
                                                    <option></option>
                                                    <option value='Male' @if($client->sex == 'Male') selected @endif>Male</option>
                                                    <option value='Female' @if($client->sex == 'Female') selected @endif>Female</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-md-6">
                                            <div>
                                                <label for="religion" class="form-label">Religion</label>
                                                <input type="text" class="form-control" id="religion" placeholder="Religion" value="{{$client->religion}}" name="religion" > 
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-md-6">
                                            <div>
                                                <label for="nationality" class="form-label">Nationality</label>
                                                <input type="text" class="form-control" id="nationality" placeholder="Nationality" value="{{$client->nationality}}" name="nationality" > 
                                            </div>
                                        </div>
                                       
                                    </div>
                                      <div class='row mb-3'>
                                        <div class="col-xxl-8 col-md-6">
                                            <div>
                                                <label for="home_address" class="form-label">Home Address</label>
                                                <textarea  class="form-control" id="home_address" placeholder="Home Address" value="" name="home_address" >{{$client->home_address}}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-xxl-4 col-md-6">
                                            <div>
                                                <label for="home_number" class="form-label">Home Number</label>
                                                <input  class="form-control" id="home_number" placeholder="Home Number" value="{{$client->home_number}}" name="home_number" >
                                            </div>
                                        </div>
                                    
                                    </div>
                                    <div class='row mb-3'>
                                        <div class="col-xxl-4 col-md-6">
                                            <div>
                                                <label for="Occupation" class="form-label">Occupation</label>
                                                <input  class="form-control" id="Occupation" placeholder="Occupation" value="{{$client->occupation}}" name="occupation" >
                                            </div>
                                        </div>
                                        <div class="col-xxl-4 col-md-6">
                                            <div>
                                                <label for="office_number" class="form-label">Office Number</label>
                                                <input  class="form-control" id="office_number" placeholder="Office Number" value="{{$client->office_number}}" name="office_number" >
                                            </div>
                                        </div>
                                        <div class="col-xxl-4 col-md-6">
                                            <div>
                                                <label for="fax_number" class="form-label">Fax Number</label>
                                                <input  class="form-control" id="fax_number" placeholder="Fax Number" value="{{$client->fax_number}}" name="fax_number" >
                                            </div>
                                        </div>
                                    
                                    </div>
                                    <div class='row mb-3'>
                                        <div class="col-xxl-4 col-md-6">
                                            <div>
                                                <label for="phone_number" class="form-label">Cell/Mobile No.</label>
                                                <input type='text' class="form-control" id="phone_number" placeholder="Phone Number" value="{{$client->mobile_number}}" name="phone_number" >
                                            </div>
                                        </div>
                                        <div class="col-xxl-4 col-md-6">
                                            <div>
                                                <label for="email_address" class="form-label">Email Address</label>
                                                <input type='email' class="form-control" id="email_address" placeholder="Email Address" value="{{$client->email_address}}" name="email_address" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class='row mb-3'>
                                        <div class="col-xxl-4 col-md-6">
                                            <div>
                                                <label for="dental_insurance" class="form-label">Dental Insurance</label>
                                                <input type='text' class="form-control" id="dental_insurance" placeholder="Company" value="{{$client->dental_insurance}}" name="dental_insurance" >
                                            </div>
                                        </div>
                                        <div class="col-xxl-4 col-md-6">
                                            <div>
                                                <label for="effective_date" class="form-label">Effective Date</label>
                                                <input type='date' class="form-control" id="effective_date" placeholder="" value="{{$client->effective_date}}" name="effective_date" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-xxl-12 col-md-12 text-end">
                                            <br>
                                            <button class="btn btn-success btn-border float-end" type="submit">Update</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end card body -->
                        </div>
                        </form>
                        <!-- end card -->

                        <!-- end card -->
                    </div>
                    <!-- ene col -->
                    <div class="col-xl-3 col-lg-4">
                         <div class="card">
                            <div class="card-body">
                            <form method='POST' action='update-location/{{$client->id}}' onsubmit="show();"  enctype="multipart/form-data">
                                @csrf   
                                <h5 class="card-title mb-4">Locations </h5> 
                               @php
                                   $loc = ($client->locations)->pluck('id')->toArray();
                                   
                               @endphp
                                <div class="row mb-2">
                                    <div class="col-lg-12">
                                        <select  id="locations"class="js-example-disabled-multi" name="locations[]"   required>
                                            @foreach($locations as $location)
                                            <option value='{{$location->id}}' @if(in_array($location->id,$loc))  selected @endif>{{$location->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                 <div class="row mb-3">
                                      <div class="col-lg-12 ">
                                            <button class="btn btn-success btn-border " type="submit">Update</button>
                                      </div>
                                 </div>
                            </form>
                            </div>
                            <!-- end card body -->
                        </div>
                        <div class="card">
                                <div class="card-header align-items-center d-flex border-bottom-dashed">
                                    <h4 class="card-title mb-0 flex-grow-1">Upload File</h4>
                                    <div class="flex-shrink-0">
                                        <button type="button" class="btn btn-soft-danger btn-sm material-shadow-none" data-bs-toggle="modal" data-bs-target="#uploadDocument"><i class="ri-upload-fill me-1 align-bottom"></i></button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    @foreach(($client->attachments)->sortByDesc('id') as $attachment)
                                    <div data-simplebar  class="mx-n3 px-3 mb-1">
                                        <div class="vstack gap-3">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1">
                                                    <h5 class="fs-13 mb-0 text-success"><a href="{{url($attachment->file)}}" target='_blank' class="text-body d-block text-success"><i class="ri-attachment-2 text-success"></i>{{$attachment->document_name}}</a></h5>
                                                </div>
                                            </div>
                                            <!-- end member item -->
                                        </div>
                                        <!-- end list -->
                                    </div>
                                    @endforeach
                                </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end tab pane -->
            <div class="tab-pane fade" id="project-activities" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Transactions</h5>
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
                                                @foreach($client->transactions->sortByDesc('id') as $transaction)
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
                    </div>
                    <!--end card-body-->
                </div>
                <!--end card-->
            </div>
            <!-- end tab pane -->
        </div>
    </div>
    <!-- end col -->
</div>
@include('clients.upload_document')
@endsection
@section('js')
 <!-- gridjs js -->
 <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>

    {{-- <script src="{{asset('inside_css/assets/js/pages/datatables.init.js')}}"></script>
    <!-- App js -->
     <script src="{{asset('inside_css/assets/libs/prismjs/prism.js')}}"></script> --}}

    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script> --}}
    <!--select2 cdn-->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="{{asset('inside_css/assets/js/pages/select2.init.js')}}"></script>
    <script src="{{asset('inside_css/assets/js/pages/datatables.init.js')}}"></script>
    <script>
        $('#example').DataTable({
    ordering: false,
    dom: 'Bfrtip',
    buttons: [
        'excel'
    ]
});
</script>
<script>
  const modal = document.getElementById('imageModal');
  const video = document.getElementById('video');
  const preview = document.getElementById('preview');
  const imageInput = document.getElementById('image_data');

  function openModal() {
    modal.style.display = 'flex';
  }

  function closeModal() {
    modal.style.display = 'none';
    stopCamera();
  }

  function handleFileUpload(event) {
     stopCamera();
    const file = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function(e) {
        preview.src = e.target.result;
        imageInput.value = e.target.result;
      };
      reader.readAsDataURL(file);
    }
  }

  function enableCamera() {
    document.getElementById('cameraSection').style.display = 'block';
    navigator.mediaDevices.getUserMedia({ video: true })
      .then(stream => {
        video.srcObject = stream;
      })
      .catch(err => {
        alert("Camera access denied: " + err);
      });
  }

  function captureImage() {
    const canvas = document.createElement('canvas');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    const ctx = canvas.getContext('2d');
    ctx.drawImage(video, 0, 0);
    const imageData = canvas.toDataURL('image/png');
    preview.src = imageData;
    imageInput.value = imageData;
    stopCamera();
  }

  function stopCamera() {
    const stream = video.srcObject;
    if (stream) {
      stream.getTracks().forEach(track => track.stop());
    }
    video.srcObject = null;
    cameraSection.style.display = 'none';
  }
  
</script>
@endsection