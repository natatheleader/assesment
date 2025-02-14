<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Timesheet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserResource;
use App\Http\Resources\TimesheetResource;

class UserController extends BaseController
{
    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->has('filters')) {
            $filters = $request->input('filters');
            
            foreach ($filters as $field => $value) {
                if (in_array($field, ['first_name', 'last_name', 'email'])) {
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

        return UserResource::collection($query->paginate(10));
    }

    /**
     * Store a new user
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);

        return new UserResource($user);
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        return new UserResource($user->load('projects'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|min:6|confirmed'
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        return new UserResource($user);
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(null, 204);
    }
}
