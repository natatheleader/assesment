<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Timesheet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserResource;
use App\Http\Resources\TimesheetResource;

class TimesheetController extends BaseController
{
    /**
     * Display a listing of timesheets
     */
    public function index(Request $request)
    {
        $query = Timesheet::with(['user', 'project']);

        if ($request->has('filters')) {
            $filters = $request->input('filters');
            
            foreach ($filters as $field => $value) {
                if (in_array($field, ['task_name', 'date', 'hours'])) {
                    if (is_array($value) && isset($value['operator'])) {
                        switch ($value['operator']) {
                            case 'LIKE':
                                $query->where($field, 'LIKE', "%{$value['value']}%");
                                break;
                            default:
                                $query->where($field, $value['operator'], $value['value']);
                        }
                    } else {
                        $query->where($field, '=', $value);
                    }
                }
            }
        }

        return TimesheetResource::collection($query->paginate(10));
    }

    /**
     * Store a new timesheet entry
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'task_name' => 'required|string|max:255',
            'date' => 'required|date',
            'hours' => 'required|numeric|min:0.5|max:24'
        ]);

        $validated['user_id'] = auth()->id();
        $timesheet = Timesheet::create($validated);

        return new TimesheetResource($timesheet->load(['user', 'project']));
    }

    /**
     * Display the specified timesheet
     */
    public function show(Timesheet $timesheet)
    {
        return new TimesheetResource($timesheet->load(['user', 'project']));
    }

    /**
     * Update the specified timesheet
     */
    public function update(Request $request, Timesheet $timesheet)
    {
        // Check if the timesheet belongs to the authenticated user
        if ($timesheet->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'project_id' => 'sometimes|exists:projects,id',
            'task_name' => 'sometimes|string|max:255',
            'date' => 'sometimes|date',
            'hours' => 'sometimes|numeric|min:0.5|max:24'
        ]);

        $timesheet->update($validated);

        return new TimesheetResource($timesheet->load(['user', 'project']));
    }

    /**
     * Remove the specified timesheet
     */
    public function destroy(Timesheet $timesheet)
    {
        // Check if the timesheet belongs to the authenticated user
        if ($timesheet->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $timesheet->delete();
        return response()->json(null, 204);
    }

    /**
     * Get timesheets for a specific project
     */
    public function projectTimesheets($projectId)
    {
        $timesheets = Timesheet::with(['user', 'project'])
            ->where('project_id', $projectId)
            ->paginate(10);

        return TimesheetResource::collection($timesheets);
    }

    /**
     * Get timesheets for a specific user
     */
    public function userTimesheets($userId)
    {
        $timesheets = Timesheet::with(['user', 'project'])
            ->where('user_id', $userId)
            ->paginate(10);

        return TimesheetResource::collection($timesheets);
    }
}
