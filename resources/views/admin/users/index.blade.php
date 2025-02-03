@extends('layouts.admin')
@section('page-title', 'User Management')
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Users</h3>
        <a href="{{ route('users.create') }}" class="btn btn-primary float-right">Add New User</a>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->roles->pluck('name')->join(', ') }}</td>
                        <td>
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
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