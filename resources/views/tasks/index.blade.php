@extends('layouts.admin')

@section('page-title', 'Task Management')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Tasks</h3>
        <a href="{{ route('tasks.create') }}" class="btn btn-primary float-right">Add New Task</a>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Assigned To</th>
                    <th>Status</th>
                    <th>Due Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tasks as $task)
                    <tr>
                        <td>{{ $task->title }}</td>
                        <td>{{ $task->assignee->name }}</td>
                        <td>{{ ucfirst($task->status) }}</td>
                        <td>{{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('j F, Y') : 'N/A' }}</td>
                        <td>
                            <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
