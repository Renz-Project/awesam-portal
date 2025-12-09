
<!-- Edit Modal -->
<div class="modal fade" id="editExpense{{ $expense->id }}" tabindex="-1" aria-labelledby="editExpenseLabel{{ $expense->id }}" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="{{ url('expenses/update', $expense->id) }}" onsubmit="show();" enctype="multipart/form-data" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title" id="editExpenseLabel{{ $expense->id }}">Edit Expense</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <div class="mb-3">
              <label class="form-label">Payee Name</label>
              <input type="text" name="name" value="{{ $expense->name }}" class="form-control" required>
          </div>
          <div class="mb-3">
              <label class="form-label">Reference Number</label>
              <input type="text" name="reference_number" value="{{ $expense->reference_number }}" class="form-control">
          </div>
          <div class="mb-3">
              <label class="form-label">Date</label>
              <input type="date" name="date" value="{{ $expense->date }}" class="form-control" required>
          </div>
          <div class="mb-3">
              <label class="form-label">Amount</label>
              <input type="number" step="0.01" name="amount" value="{{ $expense->amount }}" class="form-control" required>
          </div>
          <div class="mb-3">
              <label class="form-label">Remarks</label>
              <textarea name="remarks" class="form-control">{{ $expense->remarks }}</textarea>
          </div>
            <div class="mb-3">
                    <label for="location" class="form-label">Location</label>
                <select id="location" name='location' class="form-select" required>
                    <option value="">Select location...</option>
                    @foreach($locations as $key => $location)
                        <option value="{{$location->id}}" @if($expense->location_id == $location->id) selected @endif>{{$location->name}}</option>
                    @endforeach
                    
                </select>
            </div>
          <div class="mb-3">
              <label class="form-label">Attachment</label>
              <input type="file" name="attachment" class="form-control">
              @if($expense->attachment)
                  <small>Current: <a href="{{ asset('uploads/expenses/' . $expense->attachment) }}" target="_blank">View</a></small>
              @endif
          </div>
      </div>
      <div class="modal-footer">
        
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
    </form>
  </div>
</div>
