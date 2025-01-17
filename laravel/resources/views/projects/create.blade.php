@extends('layouts.dashboard')

@section('title', 'Create Project')
@section('header-title', 'Create Project')

@section('dashboard-content')
    <div class="container">
        <h2>Create New Project</h2>

        <form action="{{ route('projects.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="project_name">Project Name</label>
                <input type="text" class="form-control" id="project_name" name="project_name" required>
            </div>

            <div class="form-group">
                <label for="project_description">Project Description</label>
                <textarea class="form-control" id="project_description" name="project_description" rows="3" required></textarea>
            </div>

            <div class="form-group">
                <label for="deadline">Deadline</label>
                <input type="date" class="form-control" id="deadline" name="deadline" required>
            </div>

            <div class="form-group">
                <label for="files">Upload Files (optional)</label>
                    <div class="custom-file-upload">
                        <input type="file" id="files" name="files[]" multiple>
                        <label for="files" class="custom-file-upload-label">Choose files</label>
                    </div>
            </div>

            <div id="file-previews" class="mt-3"></div>

            {{-- <div class="form-group">
                <label for="commit_messages">Commit Messages (optional)</label>
                <input type="text" class="form-control" id="commit_messages" name="commit_messages[]" placeholder="Enter commit message for each file">
            </div> --}}

            <button type="submit" class="btn btn-primary">Create Project</button>
        </form>
    </div>

    <script>
        document.getElementById('files').addEventListener('change', function(event) {
            const fileInput = event.target;
            const fileList = fileInput.files;
            const filePreviews = document.getElementById('file-previews');

            filePreviews.innerHTML = '';

            Array.from(fileList).forEach((file, index) => {
                const filePreview = document.createElement('div');
                filePreview.classList.add('file-preview');

                const fileName = document.createElement('span');
                fileName.textContent = file.name;

                const removeButton = document.createElement('button');
                removeButton.type = 'button';
                removeButton.textContent = 'Cancel';
                removeButton.classList.add('btn', 'btn-danger', 'btn-sm', 'ml-2');
                removeButton.addEventListener('click', () => {
                    fileInput.value = '';
                    filePreviews.removeChild(filePreview);
                });

                filePreview.appendChild(fileName);
                filePreview.appendChild(removeButton);
                filePreviews.appendChild(filePreview);
            });
        });
    </script>
@endsection


