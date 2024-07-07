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

{{-- <script>
    document.addEventListener('DOMContentLoaded', function() {
        const editableDescription = document.getElementById('editableDescription');
        const editBtn = document.getElementById('editDescriptionBtn');
        const updateBtn = document.getElementById('updateDescriptionBtn');
        const cancelBtn = document.getElementById('cancelUpdateBtn');
        const descriptionButtons = document.getElementById('descriptionButtons');
    
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
            const updatedDescription = editableDescription.innerText.trim();
    
            fetch("{{ route('projects.update_description', $project->id) }}", {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    project_description: updatedDescription
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    console.error('Error:', data.error);
                    return;
                }
                editableDescription.setAttribute('contenteditable', 'false');
                editableDescription.innerText = updatedDescription;
                editBtn.style.display = 'block';
                descriptionButtons.style.display = 'none';
            })
            .catch(error => {
                console.error('Error updating description:', error);
            });
        });
    });
</script> --}}



@endsection