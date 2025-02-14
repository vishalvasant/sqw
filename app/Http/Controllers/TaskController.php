<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\TaskNotification;
use Illuminate\Support\Facades\Notification;

class TaskController extends Controller
{
    
    public function index()
    {
        if(auth()->id() == 1) {
            $tasks = Task::all();
        }else{
            $tasks = Task::where(function($query) {
                $query->where('assigned_to', auth()->id())
                      ->orWhere('approver_id', auth()->id());
            })->get();
        }
        return view('tasks.index', compact('tasks'));
    }


    public function create()
    {
        $users = User::all();
        return view('tasks.create', compact('users'));
    }

    public function show($id)
    {
        $task = Task::with('assignee', 'approver')->findOrFail($id);
        return view('tasks.show', compact('task'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'recurrence' => 'required|in:none,daily,weekly,monthly',
            'assigned_to' => 'required|exists:users,id',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048', // File validation
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('task_files', 'public'); // Store file
        }

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'recurrence' => $request->recurrence,
            'status' => $request->status ?? 'pending',
            'due_date' => $request->due_date,
            'assigned_to' => $request->assigned_to,
            'approver_id' => $request->approver_id,
            'file_path' => $filePath,
            'created_by' => auth()->id(),
        ]);

        // Notify Assigned User
        $assignedUser = User::find($request->assigned_to);
        $assignedUser->notify(new TaskNotification($task, 'assigned'));

        // Notify Approver
        $approver = User::find($request->approver_id);
        $approver->notify(new TaskNotification($task, 'approval required'));

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    public function approveTask($id)
    {
        $task = Task::findOrFail($id);
        if (auth()->user()->role !== 'admin') {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }
        $task->status = 'approved';
        $task->save();

        return redirect()->back()->with('success', 'Task approved successfully.');
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
            'recurrence' => 'required|in:none,daily,weekly,monthly',
            'assigned_to' => 'required|exists:users,id',
            'approver_id' => 'required|exists:users,id',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
            'status' => 'required|in:pending,in-progress,completed',
        ]);

        // Handle File Upload (If New File is Uploaded)
        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($task->file_path && Storage::disk('public')->exists($task->file_path)) {
                Storage::disk('public')->delete($task->file_path);
            }
            // Store new file
            $filePath = $request->file('file')->store('task_files', 'public');
            $task->file_path = $filePath;
        }

        // Update Task
        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'recurrence' => $request->recurrence,
            'status' => $request->status,
            'due_date' => $request->due_date,
            'assigned_to' => $request->assigned_to,
            'approver_id' => $request->approver_id,
            'created_by' => auth()->id(),
        ]);

        // Notify Assigned User (If changed)
        if ($task->wasChanged('assigned_to')) {
            $assignedUser = User::find($request->assigned_to);
            $assignedUser->notify(new TaskNotification($task, 'updated'));
        }

        // Notify Approver (If changed)
        if ($task->wasChanged('approver_id')) {
            $approver = User::find($request->approver_id);
            $approver->notify(new TaskNotification($task, 'approval required'));
        }

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

    public function report(Request $request)
    {
        $query = Task::query();

        // Filter by date range
        if ($request->has('from_date')) {
            $fromDate = $request->from_date . ' 00:00:00';
            $query->where('created_at', '>=', $fromDate);
        }
        if ($request->has('to_date')) {
            $toDate = $request->to_date . ' 23:59:59';
            $query->where('created_at', '<=', $toDate);
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $tasks = $query->with('assignee')->get();

        return view('tasks.reports', compact('tasks'));
    }
}
