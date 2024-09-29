<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request)
    {
        $projects = Project::query();

        $filters = ['name', 'department', 'status', 'start_date', 'end_date']; // Include start_date and end_date in filters

        foreach ($filters as $filter) {
            if ($request->has($filter)) {
                $projects->where($filter, $request->$filter);
            }
        }

        $projects = $projects->with('users')->get();
        return response()->json(['projects' => $projects]);
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'department' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'status' => 'required|in:pending,in_progress,completed',
            ]);

            $project = Project::create($validatedData); // Use validated data

            if ($request->has('users')) {
                $user_ids = $request->input('users', []); 
                $project->users()->attach($user_ids); 
            }

            return response()->json(['project' => $project, 'message' => 'Project created successfully'], 201);
        } catch (\Exception $e) {
            Log::error("Error creating project: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['message' => 'An error occurred while creating the project'], 500);
        }
    }

    public function show(Project $project)
    {
        return response()->json(['project' => $project->load('users')]);
    }

    public function update(Request $request, Project $project)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'sometimes|string|max:255',
                'department' => 'sometimes|string|max:255',
                'start_date' => 'sometimes|date',
                'end_date' => 'sometimes|date|after_or_equal:start_date',
                'status' => 'sometimes|in:pending,in_progress,completed',
            ]);

            $project->update($validatedData); // Use validated data

            if ($request->has('users')) {
                $user_ids = $request->input('users', []);
                $project->users()->sync($user_ids); 
            }

            return response()->json(['project' => $project, 'message' => 'Project updated successfully'], 200);
        } catch (\Exception $e) {
            Log::error("Error updating project: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['message' => 'An error occurred while updating the project'], 500);
        }
    }

    public function destroy(Project $project)
    {
        try {
            $project->users()->detach();
            $project->timesheets()->delete();
            $project->delete();
            return response()->json(['message' => 'Project deleted successfully']);
        } catch (\Exception $e) {
            Log::error("Error deleting project: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['message' => 'An error occurred while deleting the project'], 500);
        }
    }
}