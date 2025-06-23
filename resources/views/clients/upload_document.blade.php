<div class="modal fade" id="uploadDocument" tabindex="-1" aria-labelledby="uploadDocumentLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadDocumentLabel">Upload Document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="upload-document/{{$client->id}}" enctype="multipart/form-data" onsubmit="show();">
                    @csrf
                    <div class="row g-3">
                        <div class="col-lg-12">
                            <label class="form-label">Upload or Capture File</label>
                            <input 
                                type="file" 
                                class="form-control" 
                                name="file" 
                                id="fileInput_document"
                                accept="image/*,application/pdf" 
                                required
                            >
                            <small class="text-muted">Upload a PDF or photo, or take one below.</small>
                        </div>

                        <!-- Take Photo Button -->
                        <div class="col-lg-12 text-center">
                            <button type="button" class="btn btn-outline-secondary mt-2" onclick="openCamera_document()">📷 Take Photo</button>
                        </div>

                        <!-- Camera Preview -->
                        <div class="col-lg-12 d-none" id="cameraContainer_document">
                            <video id="camera_document" width="100%" autoplay playsinline class="rounded border"></video>
                            <button type="button" class="btn btn-success w-100 mt-2" onclick="capturePhoto_document()">📸 Capture</button>
                        </div>

                        <!-- Hidden Canvas -->
                        <canvas id="canvas_document" class="d-none"></canvas>

                        <div class="col-lg-12 mt-3">
                            <div class="hstack gap-2 justify-content-end">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Upload</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
let videoStream_document = null;

function openCamera_document() {
    const container = document.getElementById('cameraContainer_document');
    const video = document.getElementById('camera_document');

    container.classList.remove('d-none');

    navigator.mediaDevices.getUserMedia({ video: true })
        .then((stream) => {
            videoStream_document = stream;
            video.srcObject = stream;
        })
        .catch((err) => {
            alert("Camera access denied: " + err.message);
        });
}

function capturePhoto_document() {
    const video = document.getElementById('camera_document');
    const canvas = document.getElementById('canvas_document');
    const fileInput = document.getElementById('fileInput_document');

    const width = video.videoWidth;
    const height = video.videoHeight;

    if (!width || !height) {
        alert("Camera not ready yet.");
        return;
    }

    canvas.width = width;
    canvas.height = height;

    const ctx = canvas.getContext("2d");
    ctx.drawImage(video, 0, 0, width, height);

    canvas.toBlob(function(blob) {
        const file = new File([blob], "captured_photo.jpg", { type: "image/jpeg" });

        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);

        // Replace the input to force update
        const newInput = fileInput.cloneNode(true);
        newInput.files = dataTransfer.files;
        fileInput.parentNode.replaceChild(newInput, fileInput);
        newInput.id = "fileInput_document"; // preserve original ID

        // Stop video stream
        if (videoStream_document) {
            videoStream_document.getTracks().forEach(track => track.stop());
            videoStream_document = null;
        }

        document.getElementById('cameraContainer_document').classList.add('d-none');
        // alert("Photo captured and ready for upload.");
    }, "image/jpeg", 0.95);
}
</script>