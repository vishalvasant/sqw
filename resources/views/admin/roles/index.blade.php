{{-- Role Management UI --}}
@extends('layouts.admin')
@section('page-title', 'Role Management')
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Roles</h3>
        <a href="{{ route('roles.create') }}" class="btn btn-primary float-right"><i class="fas fa-plus-square"></i> Add New</a>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Role Name</th>
                    <th width="10%">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roles as $role)
                    <tr>
                        <td>{{ $role->id }}</td>
                        <td>{{ $role->name }}</td>
                        <td>
                            <a href="{{ route('roles.edit', $role) }}" class="btn btn-warning"><i class="fas fa-pencil-alt"></i></a>
                            <form action="{{ route('roles.destroy', $role) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection