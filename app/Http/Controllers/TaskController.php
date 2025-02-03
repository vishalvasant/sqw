<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with(['assignee', 'creator'])->get();
        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        $users = User::all();
        return view('tasks.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'required|exists:users,id',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048', // File validation
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('task_files', 'public'); // Store file
        }

        Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status ?? 'pending',
            'due_date' => $request->due_date,
            'assigned_to' => $request->assigned_to,
            'file_path' => $filePath,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    public function edit(Task $task)
    {
        $users = User::all();
        return view('tasks.edit', compact('task', 'users'));
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);

        if ($request->hasFile('file')) {
            // Delete the old file
            if ($task->file_path) {
                Storage::disk('public')->delete($task->file_path);
            }

            // Upload new file
            $filePath = $request->file('file')->store('task_files', 'public');
            $task->file_path = $filePath;
        }

        $task->update($request->except(['file']));

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    // Delete File Method
    public function deleteFile(Task $task)
    {
        if ($task->file_path) {
            Storage::disk('public')->delete($task->file_path);
            $task->file_path = null;
            $task->save();
        }

        return response()->json(['message' => 'File deleted successfully']);
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }
}
