{{-- Role Management UI --}}
@extends('layouts.admin')
@section('page-title', 'Role Management')
@section('content')
@php
    $usr = Auth::guard('web')->user();
@endphp
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Roles</h3>
        @if ($usr->can('roles.create'))
        <a href="{{ route('roles.create') }}" class="btn btn-primary float-right"><i class="fas fa-plus-square"></i> Add New</a>
        @endif
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
                            @if ($usr->can('roles.edit'))
                            <a href="{{ route('roles.edit', $role) }}" class="btn btn-warning"><i class="fas fa-pencil-alt"></i></a>
                            @endif
                            @if ($usr->can('roles.delete'))
                            <form action="{{ route('roles.destroy', $role) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i></button>
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