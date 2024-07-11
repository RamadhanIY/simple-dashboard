<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Project;
use App\Models\ProjectFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{   

    // Project
    public function index()
    {
        
        $projects = Project::with(['creator', 'updater'])->paginate(3);

        $projects->getCollection()->transform(function ($project) {
            $project->formatted_deadline = Carbon::parse($project->deadline)->format('d F Y');
            return $project;
        });

        return view('projects.index', compact('projects'));
    }

    
    public function create()
    {
        return view('projects.create');
    }

    
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

        
        if (Project::count() == 0) {
            DB::statement('ALTER TABLE users AUTO_INCREMENT = 1;');
        }

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

    public function show(Project $project)
    {
        $project->formatted_deadline = Carbon::parse($project->deadline)->format('d F Y');
        
        $project->load('files.updater');

        return view('projects.show', compact('project'));
    }

    

    public function destroy(Project $project)
    {
        foreach ($project->files as $file) {
            if ($file->file) {
                Storage::delete($file->file);
            }
            $file->delete();
        }

        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
    }
    // Edit Project on Show Project
    public function updateProjectName(Request $request, Project $project)
    {
        $request->validate([
            'project_name' => 'required|string',
        ]);

        if (!$project) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Project not found.'], 404);
            }
            return redirect()->back()->with('error', 'Project not found.');
        }

        $project->update([
            'project_name' => $request->project_name,
            'updated_by' => Auth::id(),
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => 'Project updated successfully.', 'project' => $project]);
        }

        return redirect()->route('projects.show', $project->id)->with('success', 'Project updated successfully.');
    }

    public function updateProjectDeadline(Request $request, Project $project)
    {
        $request->validate([
            'deadline' => 'required|date',
        ]);

        if (!$project) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Project not found.'], 404);
            }
            return redirect()->back()->with('error', 'Project not found.');
        }


        $project->update([
            'deadline' => $request->deadline,
            'updated_by' => Auth::id(),
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => 'Deadline updated successfully.', 'project' => $project]);
        }

        return redirect()->route('projects.show', $project->id)->with('success', 'Deadline updated successfully.');
    }

    public function updateProjectDescription(Request $request, Project $project)
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
            'updated_by' => Auth::id(),
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => 'Project updated successfully.', 'project' => $project]);
        }

        return redirect()->route('projects.show', $project->id)->with('success', 'Project updated successfully.');
    }
    
    // Project Files
    

    public function uploadFile(Request $request, Project $project)
    {   
        $request->merge(['created_by' => Auth::id()]);

        $validator = Validator::make($request->all(), [
            'files.*' => 'required|file|mimes:pdf,jpeg,png,jpg,gif,svg,doc,docx',
            function ($attribute, $value, $fail) {
                if (strlen($value->getClientOriginalName()) > 255) {
                    $fail('The ' . $attribute . ' filename is too long. It should not exceed 255 characters.');
                }
            }
        ]);
        
        if ($validator->fails()) {
            Session::flash('upload_error', 'Invalid file type or filename is too long, or no files were uploaded.');
            return redirect()->route('projects.show', $project->id);
        }

        if ($request->hasFile('files')) {

            if (ProjectFile::count() == 0) {
                DB::statement('ALTER TABLE project_files AUTO_INCREMENT = 1;');
            }

            foreach ($request->file('files') as $index => $file) {
                $path = $file->store('projects');
                $originalName = $file->getClientOriginalName();

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

        Session::flash('upload_error', 'No files were uploaded');
        return redirect()->route('projects.show', $project->id);
    }

    public function downloadFile(Project $project, ProjectFile $file)
    {
        return Storage::download($file->filepath, $file->filename);
    }

    public function updateFile(Request $request, Project $project, ProjectFile $file)
    {

        $request->validate([
            'filename' => 'required|string|max:255',
        ]);

        $currentExtension = pathinfo($file->filename, PATHINFO_EXTENSION);
        $newFilename = $request->filename;

        if (pathinfo($newFilename, PATHINFO_EXTENSION) != $currentExtension) {
            $newFilename .= '.' . $currentExtension;
        }

        $project->update([
            'updated_by' => Auth::id(),
        ]);

        $file->update([
            'filename' => $newFilename,
            'updated_by'=> Auth::id(),
        ]);


        return redirect()->route('projects.show', $project->id)->with('success', 'File name updated successfully.');
    }


    public function deleteFile(Project $project, ProjectFile $file)
    {
        Storage::delete($file->filepath);
        $file->delete();

        return redirect()->route('projects.show', $project->id)->with('success', 'File deleted successfully.');
    }
    
    
}
