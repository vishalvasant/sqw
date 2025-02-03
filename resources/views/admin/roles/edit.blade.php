@extends('layouts.admin')

@section('page-title', 'Edit Role')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Role</h3>
    </div>
    <form action="{{ route('roles.update', $role->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="form-group">
                <label for="name">Role Name</label>
                <input type="text" name="name" class="form-control" value="{{ $role->name }}" required>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Update Role</button>
        </div>
    </form>
</div>
@endsection
