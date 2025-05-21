 <div class="modal fade" id="editUser{{$user->id}}" tabindex="-1" aria-labelledby="newUserLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newUserLabel">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method='POST' action='edit-user/{{$user->id}}' onsubmit="show();"   enctype="multipart/form-data">
                     @csrf   
                    <div class="row g-3">
                        <div class="col-xxl-12">
                            <div>
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name='name' value="{{$user->name}}" placeholder="Enter Name" Required>
                            </div>
                        </div>
                        <div class="col-xxl-12">
                            <div>
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name='email' value="{{$user->email}}" placeholder="Email" Required>
                            </div>
                        </div>
                        <div class="col-xxl-12">
                            <div>
                                <label for="position" class="form-label">Position</label>
                                <input type="text" class="form-control" id="position" name='position' value="{{$user->position}}"  placeholder="position" Required>
                            </div>
                        </div>
                        <div class="col-xxl-12">
                            <div>
                                <label for="role" class="form-label">Role</label>
                                <select class='form-control' name='role' required>
                                    <option value=''>Select</option>
                                    <option value='Admin' @if($user->role == "Admin") selected @endif>Admin</option>
                                    <option value='Receptionist'  @if($user->role == "Receptionist") selected @endif >Receptionist</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xxl-12">
                                <label  class="form-label">Location</label>
                                <select class="form-control required"  data-choices data-choices-removeItem  name="locations_data[]" multiple required>
                                    @foreach($locations as $loc)
                                        <option value='{{$loc->id}}' @foreach($user->locations as $d ) @if($d->location_id == $loc->id) selected @endif @endforeach>{{$loc->name}}</option>
                                    @endforeach
                                </select>
                           
                        </div>
                        <!--end col-->
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