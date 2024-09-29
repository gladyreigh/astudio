<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Timesheet;
use Illuminate\Support\Facades\Log;

class TimesheetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request)
    {
        $timesheets = Timesheet::query();

        $filters = ['task_name', 'date', 'user_id', 'project_id'];

        foreach ($filters as $filter) {
            if ($request->has($filter)) {
                $timesheets->where($filter, $request->$filter);
            }
        }

        $timesheets = $timesheets->with('user', 'project')->get();
        return response()->json(['timesheets' => $timesheets]);
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,id',
                'project_id' => 'required|exists:projects,id',
                'task_name' => 'required|string|max:255',
                'date' => 'required|date',
                'hours' => 'required|numeric|min:0',
            ]);

            $timesheet = Timesheet::create($validatedData);
            return response()->json(['timesheet' => $timesheet, 'message' => 'Timesheet created successfully'], 201);
        } catch (\Exception $e) {
            Log::error("Error creating timesheet: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['message' => 'An error occurred while creating the timesheet'], 500);
        }
    }

    public function show(Timesheet $timesheet)
    {
        return response()->json(['timesheet' => $timesheet->load('user', 'project')]);
    }

    public function update(Request $request, Timesheet $timesheet)
    {
        try {
            $validatedData = $request->validate([
                'user_id' => 'sometimes|exists:users,id',
                'project_id' => 'sometimes|exists:projects,id',
                'task_name' => 'sometimes|string|max:255',
                'date' => 'sometimes|date',
                'hours' => 'sometimes|numeric|min:0',
            ]);

            $timesheet->update($validatedData);
            return response()->json(['timesheet' => $timesheet, 'message' => 'Timesheet updated successfully'], 200);
        } catch (\Exception $e) {
            Log::error("Error updating timesheet: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['message' => 'An error occurred while updating the timesheet'], 500);
        }
    }

    public function destroy(Timesheet $timesheet)
    {
        try {
            $timesheet->delete();
            return response()->json(['message' => 'Timesheet deleted successfully']);
        } catch (\Exception $e) {
            Log::error("Error deleting timesheet: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['message' => 'An error occurred while deleting the timesheet'], 500);
        }
    }
}