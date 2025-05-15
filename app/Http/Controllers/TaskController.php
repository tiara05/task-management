<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TaskController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $user = Auth::user();

        $tasks = Task::query();

        if ($user->role === 'admin') {
            return response()->json($tasks->get());
        }

        if ($user->role === 'manager') {
            return response()->json($tasks->where(function($query) use ($user) {
                $query->where('created_by', $user->id)
                      ->orWhere('assigned_to', $user->id);
            })->get());
        }

        // Staff
        return response()->json($tasks->where('assigned_to', $user->id)->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'assigned_to' => 'required|uuid|exists:users,id',
            'due_date' => 'required|date|after:today',
        ]);

        $assignedUser = User::find($validated['assigned_to']);
        $authUser = Auth::user();

        // Role-based assignment logic
        if ($authUser->role === 'manager' && $assignedUser->role !== 'staff') {
            return response()->json(['message' => 'Manager can only assign to staff'], 403);
        }

        if ($authUser->role === 'staff') {
            return response()->json(['message' => 'Staff cannot create tasks'], 403);
        }

        $task = Task::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'assigned_to' => $validated['assigned_to'],
            'created_by' => $authUser->id,
            'status' => 'pending',
            'due_date' => $validated['due_date'],
        ]);

        return response()->json($task, 201);
    }

    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'title' => 'string',
            'description' => 'nullable|string',
            'status' => Rule::in(['pending', 'in_progress', 'done']),
            'due_date' => 'date|after_or_equal:today',
        ]);

        $task->update($validated);

        return response()->json($task);
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
}
