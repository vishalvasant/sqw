@extends('layouts.admin')

@section('page-title', 'Create Permission')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Create New Permission</h3>
    </div>
    <form action="{{ route('permissions.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label for="name">Permission Name</label>
                <input type="text" name="name" class="form-control" placeholder="Enter permission name" required>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Create Permission</button>
        </div>
    </form>
</div>
@endsection
