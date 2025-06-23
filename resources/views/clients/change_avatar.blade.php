<div id="uploadAvatar" class="modal fade" tabindex="-1" aria-labelledby="bs-example-modal-md" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg">
    <div class="modal-content">
      <div class="modal-header d-flex align-items-center">
        <h4 class="modal-title" id="myModalLabel">Change Avatar</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form method="POST" action="{{ url('change-avatar/'.$client->id) }}" onsubmit="show()" enctype="multipart/form-data" class="validation-wizard wizard-circle">
        @csrf

        <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
          <div class="text-center">

            <input type="file" id="fileInput" accept="image/*" style="display:none" onchange="handleFileUpload(event)">

            <div class="d-flex justify-content-center gap-2 mb-3">
              <button type="button" class="btn btn-primary" onclick="document.getElementById('fileInput').click()">
                <i class="bi bi-upload"></i> Upload Image
              </button>
              <button type="button" class="btn btn-secondary" onclick="enableCamera()">
                <i class="bi bi-camera"></i> Capture via Camera
              </button>
            </div>

            <div id="cameraSection" style="display:none">
              <video id="video" autoplay class="w-100 rounded border"></video>
              <button type="button" class="btn btn-success mt-2" onclick="captureImage()">
                <i class="bi bi-check-circle"></i> Capture
              </button>
            </div>

            <div id="previewSection" class="mt-3">
              <img id="preview" src="{{ ($client->avatar) }}" onerror="this.src='{{ url('design/assets/images/profile/user-1.png') }}';" alt="Image Preview" class="img-fluid rounded border" />
            </div>

            <input type="hidden" id="image_data" name="image_data" />
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn bg-danger-subtle text-danger waves-effect" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn bg-info-subtle text-info waves-effect">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>
