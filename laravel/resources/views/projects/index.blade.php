@extends('layouts.dashboard')

@section('title', 'Projects')
@section('header')
<div class="page-header p-3">
    <h1 class="page-title h3 mb-4 text-gray-800">Project List</h1>
</div>
@endsection

@section('dashboard-content')
<div class="container">
    <a href="{{ route('projects.create') }}" class="btn btn-primary mb-2">Create New Project</a>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Deadline</th>
                <th>PIC</th>
                <th>Last Update By</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($projects as $project)
            <tr>
                <td>{{ $project->project_name }}</td>
                <td>{{ $project->project_description }}</td>
                <td>{{ $project->formatted_deadline }}</td>
                <td>{{ $project->creator->name }}</td>
                <td>{{$project->updater ? $project->updater->name : $project->creator->name}}</td>
                <td>
                    <a href="{{ route('projects.show', $project->id) }}" class="btn btn-info">View Project</a>
        
                    <button type="button" class="btn btn-danger" onclick="confirmDelete('{{ $project->id }}')">Delete</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-center">
        {{ $projects->links('pagination::bootstrap-4') }}
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteProjectModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteProjectLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete the project?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="deleteProject"  method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-primary">Confirm</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete(projectId) {
        const deleteForm = document.getElementById('deleteProject');
        deleteForm.action = `/projects/${projectId}`;
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteProjectModal'));
        deleteModal.show();
    }

</script>
@endsection
