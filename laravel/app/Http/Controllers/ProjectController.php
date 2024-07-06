<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $request->merge(['created_by' => Auth::user()->name]);

        // Validate the request data
        $request->validate([
            'project_name' => 'required|string|max:255',
            'project_description' => 'required|string',
            'deadline' => 'required|date',
            'files.*' => 'file|mimes:jpg,jpeg,png,pdf',
            'commit_messages' => 'array',
            'commit_messages.*' => 'nullable|string',
            'created_by' => 'required|string', // Ensure created_by is a valid user ID
        ]);

            // Create the project
        $project = Project::create($request->only('project_name', 'project_description', 'deadline', 'created_by'));

        // Debugging point to check if the project is created correctly
        dd($project);
    // }
    

    
        // Check if files are present in the request
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $index => $file) {
                $path = $file->store('storage', 'public');

                // Dump and die the file path and commit message to check their values
                dd($path, $request->commit_messages[$index]);

                ProjectFile::create([
                    'project_id' => $project->id,
                    'file' => $path,
                    'mime_type' => $file->getClientMimeType(),
                    'commit_message' => $request->commit_messages[$index] ?? null,
                    'created_by' => $request->created_by,
                ]);
            }
        }
    }

    //     return redirect()->route('projects.index')->with('success', 'Project created successfully.');


    //     // try {
    //     //     $project = Project::create([
    //     //         'project_name' => $request->project_name,
    //     //         'project_description' => $request->project_description,
    //     //         'deadline' => $request->deadline,
    //     //         'created_by' => $request->created_by,
    //     //     ]);

    //     //     if ($request->hasFile('files')) {
    //     //         foreach ($request->file('files') as $index => $file) {
    //     //             $path = $file->store('public/storage'); // Adjust storage path as needed
    //     //             ProjectFile::create([
    //     //                 'project_id' => $project->id,
    //     //                 'file' => $path,
    //     //                 'mime_type' => $file->getClientMimeType(),
    //     //                 'commit_message' => $request->commit_messages[$index] ?? null,
    //     //                 'created_by' => $request->created_by,
    //     //             ]);
    //     //         }
    //     //     }

    //     //     return redirect()->route('projects.index')->with('success', 'Project created successfully.');
    //     // } catch (\Exception $e) {
    //     //     // Log the exception to Laravel log file
    //     //     \Log::error('Error creating project: ' . $e->getMessage());
    
    //     //     return back()->withInput()->withErrors(['error' => 'Error creating project. Please try again.']);
    //     // }
    // }

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
