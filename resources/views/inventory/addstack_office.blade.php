 <div class="modal fade" id="newStack-{{$key}}" tabindex="-1" aria-labelledby="newUserLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-success" id="newUserLabel">Add Stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method='POST' action='{{url('office-supplies/new-stock')}}' onsubmit="show();"  enctype="multipart/form-data">
                     @csrf   
                     <div class="row g-3">
                        <div class="col-lg-12">
                            <input type="hidden" name="product_id" value="{{$row['product_id']}}" required>
                            <input type="hidden" name="location_id" value="{{$row['location_id']}}" required>
                            <input type="hidden" name="type" value="inflow" required>
                            <div>
                                <label for="name" class="form-label">Qty</label>
                                <input type="number" min="0" name="quantity" class="form-control" required>
                            </div>

                        </div>
                     </div>
                    <div class="row g-3">
                        <div class="col-lg-12">
                            <div>
                                <label for="remarks" class="form-label">Remarks</label>
                                <textarea class="form-control" id="remarks" name='remarks' placeholder="Remarks" rows="3"></textarea>
                            </div>
                        </div>
                     </div>
                    <div class="row g-3">
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