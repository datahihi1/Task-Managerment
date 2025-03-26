<?php

namespace App\Http\Controllers;

use App\Events\TaskUpdated;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::query()->with('assignedUser:id,name');
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }
        if ($request->has('assigned_to_me') && $request->assigned_to_me) {
            $query->where('assigned_to', $request->user()->id);
        }
        return response()->json($query->get());
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
        broadcast(new TaskUpdated($task))->toOthers();
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
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'in:pending,in_progress,completed'
        ]);
    
        $task->update($validated);
        broadcast(new TaskUpdated($task))->toOthers();
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
        broadcast(new TaskUpdated($task))->toOthers();
        return response()->json(['message' => 'Công việc đã bị xóa']);
    }
    
}
