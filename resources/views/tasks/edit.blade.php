@extends('layouts.admin')

@section('page-title', 'Edit Task')

@section('content')
@php
    $usr = Auth::guard('web')->user();
@endphp
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Task</h3>
    </div>
    <form action="{{ route('tasks.update', $task->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="form-group">
                <label for="title">Task Title</label>
                <input type="text" name="title" class="form-control" value="{{ $task->title }}" required>
            </div>

            <div class="form-group">
                <label for="description">Task Description</label>
                <textarea name="description" class="form-control" rows="4" required>{{ $task->description }}</textarea>
            </div>
            @if ($task->file_path)
                <div class="mt-3">
                    <a href="{{ asset('storage/' . $task->file_path) }}" target="_blank" class="btn btn-primary btn-sm">View File</a>
                    <a href="{{ asset('storage/' . $task->file_path) }}" download class="btn btn-success btn-sm">Download File</a>
                    <button type="button" class="btn btn-danger btn-sm delete-file" data-task-id="{{ $task->id }}">Delete File</button>
                    <input type="hidden" id="taskId" name="taskId" value="{{$task->id}}">
                </div>    
            @else
                <div class="form-group">
                    <label for="file">Attach File</label>
                    <input type="file" name="file" class="form-control">
                </div>
            @endif
            <div class="form-group">
                <label for="assigned_to">Assign To</label>
                <select name="assigned_to" class="form-control" required>
                    <option value="">Select User</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ $task->assigned_to == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            @if ($usr->id == 1 || $usr->id == $task->approver_id)
            <div class="form-group">
                <label for="approver_id">Approver</label>
                <select name="approver_id" class="form-control" required>
                    <option value="">Select User</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ $task->approver_id == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif
            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" class="form-control">
                    <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>Completed</option>
                    @if ($usr->id == 1 || $usr->id == $task->approver_id)
                    <option value="approved" {{ $task->status == 'completed' ? 'selected' : '' }}>Completed</option>
                    @endif
                </select>
            </div>
            <div class="form-group">
                <label for="recurrence">Recurrence</label>
                <select name="recurrence" class="form-control">
                    <option value="none" {{ $task->recurrence == 'none' ? 'selected' : '' }}>None</option>
                    <option value="daily" {{ $task->recurrence == 'daily' ? 'selected' : '' }}>Daily</option>
                    <option value="weekly" {{ $task->recurrence == 'weekly' ? 'selected' : '' }}>Weekly</option>
                    <option value="monthly" {{ $task->recurrence == 'monthly' ? 'selected' : '' }}>Monthly</option>
                </select>
            </div>
            <div class="form-group">
                <label for="deadline">Deadline</label>
                <input type="date" name="due_date" class="form-control" value="{{ \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') }}" required>
            </div>
        </div>
        
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Update Task</button>
            <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
<!-- JavaScript for Delete File -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
    let deleteButton = document.querySelector(".delete-file");

    if (deleteButton) {
        deleteButton.addEventListener("click", function () {
            let taskId = document.querySelector("#taskId").value;

            if (confirm("Are you sure you want to delete the attached file?")) {
                fetch(`http://localhost/sqw/public/tasks/${taskId}/delete-file`, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": <?php echo json_encode(csrf_token()); ?>,
                        "Content-Type": "application/json"

                    }
                }).then(response => location.reload());
            }
        });
    }
});
</script>