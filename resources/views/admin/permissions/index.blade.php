{{-- resources/views/admin/permissions/index.blade.php --}}
@extends('layouts.admin')
@section('page-title', 'Assign Permissions')
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Assign Permissions to Roles</h3>
    </div>
    <form action="{{ route('permissions.update' , $permissions) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            @foreach ($roles as $role)
                <h4>{{ $role->name }}</h4>
                <div class="row">
                    @foreach ($permissions as $permission)
                        <div class="col-md-4">
                            <div class="form-check">
                                <input type="checkbox" name="permissions[{{ $role->id }}][]" value="{{ $permission->id }}"
                                    {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                <label>{{ $permission->name }}</label>
                            </div>
                        </div>
                    @endforeach
                </div>
                <hr>
            @endforeach
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Update Permissions</button>
        </div>
    </form>
</div>
@endsection