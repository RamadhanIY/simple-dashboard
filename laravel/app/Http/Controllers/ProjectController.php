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
            'created_by' => 'required',
        ]);

            // Create the project
        $project = Project::create($request->only('project_name', 'project_description', 'deadline', 'created_by'));

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $index => $file) {
                $path = $file->store('projects');
                $originalName = $file->getClientOriginalName();
                ProjectFile::create([
                    'project_id' => $project->id,
                    'filename' => $originalName,
                    'filepath'=>$path,
                    'mime_type' => $file->getClientMimeType(),
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

    public function uploadFile(Request $request, Project $project)
    {   
        $request->merge(['created_by' => Auth::id()]);

        $request->validate([
            'files.*' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validate each file
        ]);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $index => $file) {
                $path = $file->store('projects'); // Store file in storage/app/projects directory
                $originalName = $file->getClientOriginalName();

                // Create ProjectFile instance and save to database
                $projectFile = new ProjectFile();
                $projectFile->project_id = $project->id;
                $projectFile->filename = $originalName;
                $projectFile->filepath = $path;
                $projectFile->mime_type = $file->getClientMimeType();
                $projectFile->created_by = $request->created_by; 
                $projectFile->save();
            }

            return redirect()->route('projects.show', $project->id)->with('success', 'Project updated successfully.');
        }

        return response()->json(['success' => false, 'message' => 'No files were uploaded'], 400);
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
