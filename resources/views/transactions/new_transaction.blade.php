<!-- New Transaction Modal -->
<div class="modal fade" id="newTransactionModal" tabindex="-1" aria-labelledby="newTransactionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="newTransactionModalLabel">New Transaction</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
         <form method='POST' action='new-transaction' onsubmit="show();"   enctype="multipart/form-data">
        @csrf   
            <div class="modal-body">
            <div class="row g-3">
                <!-- Client (Select2) -->
                <div class="col-md-12">
                <label for="client" class="form-label">Client</label>
                <select id="client" class="form-select" name='client_id' required>
                    <option value="">Search client...</option>
                    @foreach($clients as $client)
                    <option value="{{$client->id}}">{{$client->last_name}}, {{$client->first_name}}</option>
                    @endforeach
                </select>
                </div>

                <!-- Dentist -->
                <div class="col-md-4">
                <label for="dentist" class="form-label">Dentist</label>
                <input type="text" name="dentist" class="form-control" placeholder="Dentist/AD" required>
                </div>
                <div class="col-md-4">
                <label for="dentist" class="form-label">Dentist 2 (optional)</label>
                <input type="text" name="dentist_2" class="form-control" placeholder="Dentist/AD">
                </div>
                <div class="col-md-4">
                <label for="dentist" class="form-label">Dentist 3 (optional)</label>
                <input type="text" name="dentist_3" class="form-control" placeholder="Dentist/AD">
                </div>

                <!-- Treatment Items Container -->
                 <div class="col-md-6">
                        
                        <label class="form-label">Service</label>
                 </div>
                   <div class="col-md-4">

                        <label class="form-label">Amount</label>
                   </div>
                <div id="treatment-items">
                    <div class="treatment-item row g-3 align-items-end mb-2">
                        <div class="col-md-6">
                        <input type="text" name="treatment[]" class="form-control" placeholder="Service name" required>
                        </div>
                        <div class="col-md-4">
                        <input type="number" name="amount[]" class="form-control" placeholder="0.00" required>
                        </div>
                        <div class="col-md-2">
                        <button type="button" class="btn btn-danger btn-remove-treatment w-100">
                            <i class="ri-delete-bin-6-line"></i>
                        </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <button type="button" id="addTreatmentBtn" class="btn btn-outline-primary ">
                        <i class="ri-add-line"></i> Add More Treatment
                    </button>
                </div>
                
                 <div class="col-md-5">
                        
                        <label class="form-label">Product</label>
                 </div>
                   <div class="col-md-2">

                        <label class="form-label">Qty</label>
                   </div>
                   <div class="col-md-3">
                        <label class="form-label">Total Amount</label>
                   </div>
                    <div id="product-items">
                        <div class="product-item row g-3 align-items-end mb-2">
                        </div>
                    </div>
                        <div class="col-md-12">
                    <button type="button" id="addProductBtn" class="btn btn-outline-primary ">
                        <i class="ri-add-line"></i> Add More Product
                    </button>
                </div>
                <!-- Add More Treatment -->
             

                <!-- Payment Type -->
                <div class="col-md-4">
                <label for="paymentType" class="form-label">Payment Type</label>
                <select id="paymentType" name='type' class="form-select" required>
                    <option value="">Select type...</option>
                    <option value="cash" selected>Cash</option>
                    <option value="gcash">GCash</option>
                    <option value="HMO">HMO</option>
                    <option value="CC">CC</option>
                    <option value="Others">Others</option>
                </select>
                </div>
                <div class="col-md-4">
                <label for="paymentType" class="form-label">Location</label>
                <select id="paymentType" name='location' class="form-select" required>
                    <option value="">Select location...</option>
                    @foreach($locations as $key => $location)
                        <option value="{{$location->id}}" @if($key == 0) selected @endif>{{$location->name}}</option>
                    @endforeach
                    
                </select>
                </div>
                <div class="col-md-4">
                <label for="paymentType" class="form-label">Date</label>
                <input type='date' name='date' class='form-control' value='{{date("Y-m-d")}}' max='{{date("Y-m-d")}}' readonly  required>
                </div>

                <!-- Remarks -->
                <div class="col-md-12">
                <label for="remarks" class="form-label">Remarks</label>
                <textarea class="form-control" id="remarks" name='remarks' rows="3" placeholder="Additional notes..."></textarea>
                </div>
            </div>
            </div>

            <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Save Transaction</button>
            </div>
      </form>
    </div>
  </div>
</div>
