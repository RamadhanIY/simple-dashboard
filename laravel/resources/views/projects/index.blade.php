@extends('layouts.dashboard')

@section('title', 'Projects')
@section('header')
<div class="page-header">
    <h1 class="h3 mb-4 text-gray-800">Project List</h1>
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
                    <form action="{{ route('projects.destroy', $project->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
