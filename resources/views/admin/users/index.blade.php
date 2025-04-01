@extends('layouts.admin')
@section('page-title', 'User Management')
@section('content')
@php
    $usr = Auth::guard('web')->user();
@endphp
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Users</h3>
        @if ($usr->can('users.create'))
        <a href="{{ route('users.create') }}" class="btn btn-primary float-right"><i class="fas fa-plus-square"></i> Add New</a>
        @endif
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th width="10%">Actions</th>
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
                            @if ($usr->can('users.edit'))
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-warning"><i class="fas fa-pencil-alt"></i></a>
                            @endif
                            @if ($usr->can('users.delete'))
                            <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="if(confirm('Are you sure you want to delete this?')) { this.form.submit(); }"><i class="fas fa-trash"></i></button>
                            </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection