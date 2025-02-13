@extends('layouts.admin')

@section('page-title', 'Task Details')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Task Details</h3>
    </div>
    <div class="card-body">
        <div class="form-group">
            <label><strong>Task Title:</strong></label>
            <p>{{ $task->title }}</p>
        </div>

        <div class="form-group">
            <label><strong>Description:</strong></label>
            <p>{{ $task->description }}</p>
        </div>

        <div class="form-group">
            <label><strong>Assigned To:</strong></label>
            <p>{{ $task->assignedTo->name ?? 'Not Assigned' }}</p>
        </div>

        <div class="form-group">
            <label><strong>Approver:</strong></label>
            <p>{{ $task->approver->name ?? 'Not Assigned' }}</p>
        </div>

        <div class="form-group">
            <label><strong>Due Date:</strong></label>
            <p>{{ $task->due_date }}</p>
        </div>

        <div class="form-group">
            <label><strong>Status:</strong></label>
            <span class="badge badge-{{ $task->status == 'pending' ? 'warning' : 'success' }}">
                {{ ucfirst($task->status) }}
            </span>
        </div>

        @if($task->file)
        <div class="form-group">
            <label><strong>Attached File:</strong></label>
            <p><a href="{{ asset('storage/' . $task->file) }}" target="_blank" class="btn btn-info btn-sm">View File</a></p>
        </div>
        @endif
    </div>
    <div class="card-footer">
        <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Back to List</a>
        @if(auth()->user()->id == $task->approver_id)
            <form action="{{ route('tasks.approve', $task->id) }}" method="POST" class="d-inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-success">Approve Task</button>
            </form>
        @endif
    </div>
</div>
@endsection
