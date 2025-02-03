@extends('layouts.admin')

@section('page-title', 'Edit User')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit User</h3>
    </div>
    <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
            </div>
            <div class="form-group">
                <label for="role">Assign Role</label>
                <select name="role_id" class="form-control">
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Update User</button>
        </div>
    </form>
</div>
@endsection
