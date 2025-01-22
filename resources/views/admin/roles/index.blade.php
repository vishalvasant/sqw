@extends('layouts.admin')
@section('page-title', 'Roles')
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Role Management</h3>
        <a href="{{ route('roles.create') }}" class="btn btn-primary float-right">Add Role</a>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Role Name</th>
                    <th>Permissions</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($roles as $role)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $role->name }}</td>
                    <td>{{ implode(', ', $role->permissions->pluck('name')->toArray()) }}</td>
                    <td>
                        <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">No roles found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-2">
            {{ $roles->links() }}
        </div>
    </div>
</div>
@endsection
