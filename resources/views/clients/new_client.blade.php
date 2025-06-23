 <div class="modal fade" id="newClient" tabindex="-1" aria-labelledby="newUserLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newUserLabel">New Client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method='POST' action='{{url('new-client')}}' onsubmit="show();"  enctype="multipart/form-data">
                     @csrf   
                    <div class="row g-3">
                        <div class="col-xxl-12">
                            <div>
                                <label for="name" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="name" name='first_name' placeholder="First Name" Required>
                            </div>
                        </div>
                        <div class="col-xxl-12">
                            <div>
                                <label for="middle_name" class="form-label">Middle Name (optional)</label>
                                <input type="text" class="form-control" id="middle_name" name='middle_name' placeholder="Middle Name" >
                            </div>
                        </div>
                        <div class="col-xxl-12">
                            <div>
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name='last_name'  placeholder="Last Name" Required>
                            </div>
                        </div>
                    
                        <div class="col-xxl-12">
                            <div>
                                <label for="location" class="form-label">Location</label>
                                <select class="form-control required" id="locations" data-choices   name="locations[]" required>
                                    @foreach($locations as $location)
                                    <option value='{{$location->id}}' selected>{{$location->name}}</option>
                                    @endforeach
                                </select>
                              
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="hstack gap-2 justify-content-end">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->
                </form>
            </div>
        </div>
    </div>
</div>