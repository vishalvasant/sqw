@extends('layouts.admin')

@section('page-title', 'Create Task')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Create Task</h3>
    </div>
    <form action="{{ route('tasks.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label for="title">Task Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="description">Task Description</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label for="file">Attach File</label>
                <input type="file" name="file" class="form-control">
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="assigned_to">Assign To</label>
                        <select name="assigned_to" class="form-control" required>
                            <option value="">Select User</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="approver_id">Approver</label>
                        <select name="approver_id" class="form-control" required>
                            <option value="">Select Approver</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="due_date">Due Date</label>
                        <input type="date" name="due_date" class="form-control">
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="recurrence">Recurrence</label>
                        <select name="recurrence" class="form-control">
                            <option value="none">None</option>
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                        </select>
                    </div>
                </div>
            </div>
            
            
        </div>
        <div class="card-footer">
            <div class="row float-right">
                <button type="submit" class="btn btn-primary">Create Task</button>
            </div>
        </div>
    </form>
</div>
@endsection
