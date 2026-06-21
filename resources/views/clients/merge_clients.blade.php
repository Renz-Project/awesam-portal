<div class="modal fade" id="mergeClients" tabindex="-1" aria-labelledby="mergeClientsLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mergeClientsLabel">Merge Duplicate Clients</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="mergeClientsForm" method="POST" action="{{ route('clients.merge') }}">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning">
                        Transactions, attachments, and locations from the duplicate client will be moved to the client you keep. The duplicate client record will be deleted.
                    </div>

                    <div class="mb-3">
                        <label for="primary_client_id" class="form-label">Client to Keep</label>
                        <select id="primary_client_id" name="primary_client_id" class="form-control" required></select>
                    </div>

                    <div class="mb-3">
                        <label for="duplicate_client_id" class="form-label">Duplicate Client to Remove</label>
                        <select id="duplicate_client_id" name="duplicate_client_id" class="form-control" required></select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="ri-git-merge-line"></i> Merge Clients
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
