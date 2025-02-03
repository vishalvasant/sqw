@extends('layouts.admin')

@section('page-title', 'Create Role')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Create New Role</h3>
    </div>
    <form action="{{ route('roles.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label for="name">Role Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Create Role</button>
        </div>
    </form>
</div>
@endsection
