<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with('assignedUser:id,name')->get();
        return response()->json($tasks);
    }

    public function store(Request $request)
    {
        $task = Task::create($request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'in:pending,in_progress,completed',
            'due_date' => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
        ]));

        return response()->json($task, 201);
    }

    public function update(Request $request, $id)
    {
        $task = Task::find($id);
        if (!$task) {
            return response()->json(['message' => 'Công việc không tồn tại'], 404);
        }
    
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id'
        ]);
    
        $task->update($validated);
    
        return response()->json($task);
    }
    
    public function show($id)
    {
        $task = Task::with('assignedUser:id,name')->find($id);

        if (!$task) {
            return response()->json(['message' => 'Công việc không tồn tại'], 404);
        }

        return response()->json($task);
    }


    public function destroy($id)
    {
        $task = Task::find($id);
        if (!$task) {
            return response()->json(['message' => 'Công việc không tồn tại'], 404);
        }
    
        $task->delete();
        return response()->json(['message' => 'Công việc đã bị xóa']);
    }
    
}
