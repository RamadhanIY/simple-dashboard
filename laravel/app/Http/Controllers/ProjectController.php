<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectFile;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::all();
        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new project.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * Store a newly created project in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'project_name' => 'required|string|max:255',
            'project_description' => 'required|string',
            'deadline' => 'required|date',
            'files.*' => 'file|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'commit_messages' => 'array',
        ]);

        $project = Project::create($request->only('project_name', 'project_description', 'deadline', 'created_by'));

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $index => $file) {
                $path = $file->store('projects');
                ProjectFile::create([
                    'project_id' => $project->id,
                    'file' => $path,
                    'mime_type' => $file->getClientMimeType(),
                    'commit_message' => $request->commit_messages[$index],
                    'created_by' => $request->created_by,
                ]);
            }
        }

        return redirect()->route('projects.index')->with('success', 'Project created successfully.');
    }

    /**
     * Display the specified project.
     *
     * @param  Project  $project
     * @return \Illuminate\View\View
     */
    public function show(Project $project)
    {
        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified project.
     *
     * @param  Project  $project
     * @return \Illuminate\View\View
     */
    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    /**
     * Update the specified project in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Project  $project
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Project $project)
    {
        $request->validate([
            'project_name' => 'required|string|max:255',
            'project_description' => 'required|string',
            'deadline' => 'required|date',
            'files.*' => 'file|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'commit_messages' => 'array',
        ]);

        $project->update($request->only('project_name', 'project_description', 'deadline', 'updated_by'));

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $index => $file) {
                $path = $file->store('projects');
                ProjectFile::create([
                    'project_id' => $project->id,
                    'file' => $path,
                    'mime_type' => $file->getClientMimeType(),
                    'commit_message' => $request->commit_messages[$index],
                    'created_by' => $request->updated_by,
                ]);
            }
        }

        return redirect()->route('projects.index')->with('success', 'Project updated successfully.');
    }

    /**
     * Remove the specified project from storage.
     *
     * @param  Project  $project
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Project $project)
    {
        foreach ($project->files as $file) {
            Storage::delete($file->file);
            $file->delete();
        }

        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
    }
}
