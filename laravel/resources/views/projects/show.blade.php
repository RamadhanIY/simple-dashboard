@extends('layouts.dashboard')

@section('title', 'Projects')
@section('header')

@section('dashboard-content')

<div class="container">
    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <h1 class="h4 text-gray-900 mb-4 px-4 pt-3">{{$project->project_name}}</h1>
            <div class="row px-4 pb-3">
                <div class="col-sm-6 mb-3 mb-sm-0">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">PIC Project</h6>
                            <p class="card-label">{{$project->creator->name}}</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Due Date Project</h6>
                            <p class="card-label">{{$project->formatted_deadline}}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Description --}}
            <div class="container">
                <div class="d-flex justify-content-between align-items-center px-2 pt-3">
                    <h3 class="h5 text-gray-900 mb-0">Description</h3>
                    <button id="editDescriptionBtn" type="button" class="btn btn-primary btn-sm">Edit</button>
                </div>
                <form id="descriptionForm" action="{{ route('projects.update_description', $project->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div id="editableDescription" class="px-2 py-3" contenteditable="false">
                        {{ $project->project_description }}
                    </div>
                    <input type="hidden" name="project_description" id="projectDescriptionInput">
                    <div id="descriptionButtons" class="px-2 py-3" style="display: none;">
                        <button id="updateDescriptionBtn" type="button" class="btn btn-primary">Update Description</button>
                        <button id="cancelUpdateBtn" type="button" class="btn btn-secondary ml-2">Cancel</button>
                    </div>
                </form>
            </div>

            {{-- Attachment FIle --}}
            <div class="container">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="h5 text-gray-900 mb-0">Uploaded Files</h3>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            @foreach($project->files as $file)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $file->filename }}
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('projects.download_file', ['project' => $project->id, 'file' => $file->id]) }}" class="btn btn-sm btn-outline-primary">Download</a>
                                        <button type="button" class="btn btn-sm btn-outline-secondary">Edit</button>
                                        <button type="button" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h3 class="h5 text-gray-900 mb-0">Add New Files</h3>
                    </div>
                    <div class="card-body">
                        <form id="uploadForm" action="{{ route('projects.upload_file', $project->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('POST') 
                            <div>
                                <div class="custom-file-upload">
                                    <input type="file" id="files" name="files[]" multiple>
                                    <label for="files" class="custom-file-upload-label">Choose files</label>
                                </div>
                            </div>
                            <div id="file-previews" class="mt-3"></div>
                            <button id="submitFilesBtn" type="submit" class="btn btn-primary mt-3">Upload Files</button>
                        </form>
                
                       
                        {{-- <!-- Dropzone for adding new files -->
                        <form class="form" action="{{ route('projects.upload_file', $project->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="dropzone" id="kt_dropzonejs_example_2">
                                <div class="dz-message needsclick">
                                    <i class="ki-duotone ki-file-up fs-3x text-primary"><span class="path1"></span><span class="path2"></span></i>
                                    <div class="ms-4">
                                        <h3 class="fs-5 fw-bold text-gray-900 mb-1">Drop files here or click to upload.</h3>
                                        <span class="fs-7 fw-semibold text-gray-500">Upload up to 10 files</span>
                                    </div>
                                </div>
                            </div>
                            <button id="submitFilesBtn" type="button" class="btn btn-primary mt-3">Upload Files</button>
                        </form> --}}
                    </div>
                </div>
            
        </div>
    </div>
</div>

<!-- Update Confirmation Modal -->
<div id="updateModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Confirm Update</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to update the description?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" id="confirmUpdateBtn" class="btn btn-primary">Confirm</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Upload Files -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">Upload Successful</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Files uploaded successfully.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editableDescription = document.getElementById('editableDescription');
        const editBtn = document.getElementById('editDescriptionBtn');
        const updateBtn = document.getElementById('updateDescriptionBtn');
        const cancelBtn = document.getElementById('cancelUpdateBtn');
        const descriptionButtons = document.getElementById('descriptionButtons');
        const descriptionForm = document.getElementById('descriptionForm');
        const projectDescriptionInput = document.getElementById('projectDescriptionInput');
        const updateModal = new bootstrap.Modal(document.getElementById('updateModal'));
    
        editBtn.addEventListener('click', function() {
            editableDescription.setAttribute('contenteditable', 'true');
            editableDescription.focus();
            editBtn.style.display = 'none';
            descriptionButtons.style.display = 'block';
        });
    
        cancelBtn.addEventListener('click', function() {
            editableDescription.setAttribute('contenteditable', 'false');
            editableDescription.innerText = "{{ $project->project_description }}";
            editBtn.style.display = 'block';
            descriptionButtons.style.display = 'none';
        });
    
        updateBtn.addEventListener('click', function() {
            projectDescriptionInput.value = editableDescription.innerText.trim();
            updateModal.show();
        });
    
        document.getElementById('confirmUpdateBtn').addEventListener('click', function() {
            updateModal.hide();
            descriptionForm.submit();
        });
    });
</script>

<script>
    document.getElementById('files').addEventListener('change', function(event) {
        const fileInput = event.target;
        const fileList = fileInput.files;
        const filePreviews = document.getElementById('file-previews');

        filePreviews.innerHTML = '';

        Array.from(fileList).forEach(file => {
            const fileName = document.createElement('div');
            fileName.textContent = file.name;
            filePreviews.appendChild(fileName);
        });
    });

    document.getElementById('submitFilesBtn').addEventListener('click', function() {
        document.getElementById('uploadForm').submit();
        $('#successModal').modal('show'); // Show the success modal after form submission
    });
</script>

{{-- <script>
    Dropzone.autoDiscover = false;

    var myDropzone = new Dropzone("#kt_dropzonejs_example_2", {
        url: "{{ route('projects.upload_file', $project->id) }}",
        paramName: "file",
        maxFiles: 10,
        maxFilesize: 10, // MB
        addRemoveLinks: true,
        
        init: function () {
            var submitButton = document.querySelector("#submitFilesBtn");
            var successModal = new bootstrap.Modal(document.getElementById('successModal'), {
                keyboard: false
            });

            submitButton.addEventListener("click", function () {
                myDropzone.processQueue(); // Trigger file upload process

                // Handle success event
                this.on("success", function (file, response) {
                    successModal.show(); // Show success modal on successful upload
                });

                // Handle complete event (after all files are uploaded)
                this.on("complete", function (file) {
                    myDropzone.removeFile(file); // Remove file from Dropzone
                });
            });
        }
    });
</script> --}}




@endsection