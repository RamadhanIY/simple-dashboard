<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with(['creator', 'updater'])->get();
        $projects = Project::all()->map(function ($project) {
            $project->formatted_deadline = Carbon::parse($project->deadline)->format('d F Y');
            return $project;
        });
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
        $request->merge(['created_by' => Auth::id()]);

        // Validate the request data
        $request->validate([
            'project_name' => 'required|string|max:255',
            'project_description' => 'required|string',
            'deadline' => 'required|date',
            'files.*' => 'file|mimes:jpg,jpeg,png,pdf',
            'commit_messages' => 'array',
            'commit_messages.*' => 'nullable|string',
            'created_by' => 'required', // Ensure created_by is a valid user ID
        ]);

            // Create the project
        $project = Project::create($request->only('project_name', 'project_description', 'deadline', 'created_by'));

        // Debugging point to check if the project is created correctly
        // dd($project);
    // }
    

    
        // Check if files are present in the request
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $index => $file) {
                $path = $file->store('storage', 'public');

                // Dump and die the file path and commit message to check their values
                // dd($path, $request->commit_messages[$index]);

                ProjectFile::create([
                    'project_id' => $project->id,
                    'file' => $path,
                    'mime_type' => $file->getClientMimeType(),
                    'commit_message' => $request->commit_messages[$index] ?? null,
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
        $project->formatted_deadline = Carbon::parse($project->deadline)->format('d F Y');
        
        $project->load('files.updater');

        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified project.
     *
     * @param  Project  $project
     * @return \Illuminate\View\View
     */
    
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

        $project->update([
            'project_name' => $request->project_name,
            'project_description' => $request->project_description,
            'deadline' => $request->deadline,
            'updated_by' => $request->updated_by,
        ]);

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

    public function update_description(Request $request, Project $project)
    {
        $request->validate([
            'project_description' => 'required|string',
        ]);

        if (!$project) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Project not found.'], 404);
            }
            return redirect()->back()->with('error', 'Project not found.');
        }

        $project->update([
            'project_description' => $request->project_description,
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => 'Project updated successfully.', 'project' => $project]);
        }

        return redirect()->route('projects.show', $project->id)->with('success', 'Project updated successfully.');
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
