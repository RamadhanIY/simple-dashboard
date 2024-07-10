@extends('layouts.dashboard')

@section('title', 'Projects')
@section('header')

@section('dashboard-content')

<div class="container">
    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <div class="d-flex justify-content-between align-items-center pt-3 pb-3">
                <h1 class="h3 text-gray-900 mb-0 ms-4">{{$project->project_name}}</h1>
                <button id="editNameBtn" type="button" class="btn btn-primary btn-sm me-5">Edit</button> 
            </div>

            <div id="editNameForm" style="display: none;" class="card-body pt-3 pb-3">
                <form action="{{ route('projects.update_name', $project->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="input-group">
                        <input type="text" class="form-control" name="project_name" value="{{ $project->project_name }}" required>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
            
            <div class="row px-4 pb-3">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center pt-3 pb-3">
                                    <h3 class="h5 text-gray-900 mb-0">Created By</h3>
                                </div>
                                <p class="card-label p-2">{{$project->creator->name}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between py-3">
                                    <h3 class="h5 text-gray-900 mb-0">Deadline</h3>
                                    <button id="editDeadlineBtn" type="button" class="btn btn-primary btn-sm">Edit</button>
                                </div>
                                <div id="editDeadlineForm" style="display: none;" class="card-body pt-3 pb-3">
                                    <form action="{{ route('projects.update_deadline', $project->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="input-group">
                                            <input type="date" class="form-control" name="deadline" value="{{ $project->deadline }}" required>
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-label mb-3 d-flex align-items-center justify-content-center" style="width: {{ strlen($project->formatted_deadline) * 1 }}rem; height: auto">
                                    <h6 class="label-title mb-0">{{$project->formatted_deadline}}</h6>
                                </div>
                            </div>

                            
                
                           
                            
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
                                        <a href="{{ route('projects.files.download', ['project' => $project->id, 'file' => $file->id]) }}" class="btn btn-sm btn-outline-primary">Download</a>
                                        <button class="btn btn-sm btn-outline-secondary" onclick="showEditModal('{{ $file->id }}', '{{ $file->filename }}', '{{ $project->id }}')">Edit</button>
                                        <button class="btn btn-sm btn-outline-danger" onclick="showDeleteModal('{{ $file->id }}', '{{ $project->id }}')">Delete</button>
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

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete the file?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-primary">Confirm</button>
                </form>
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


<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit File Name</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="filename" class="form-label">File Name</label>
                        <input type="text" class="form-control" id="filename" name="filename" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </form>
    </div>
</div>


<!-- Modal for error messages -->
<div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="errorModalLabel">Error</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{ session('upload_error') }}
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

    function showEditModal(fileId, fileName, projectId) {
        const editForm = document.getElementById('editForm');
        editForm.action = `/projects/${projectId}/files/${fileId}`;
        document.getElementById('filename').value = fileName;
        const editModal = new bootstrap.Modal(document.getElementById('editModal'));
        editModal.show();
    }

    function showDeleteModal(fileId, projectId) {
        const deleteForm = document.getElementById('deleteForm');
        deleteForm.action = `/projects/${projectId}/files/${fileId}`;
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }
</script>

@if (session('upload_error'))
    <script>
        $(document).ready(function() {
            $('#errorModal').modal('show');
        });
    </script>
@endif

<script>
    $(document).ready(function() {
        $('#editNameBtn').click(function() {
            $('#editNameForm').toggle();
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#editDeadlineBtn').click(function() {
            $('#editDeadlineForm').toggle();
        });
    });
</script>

@endsection