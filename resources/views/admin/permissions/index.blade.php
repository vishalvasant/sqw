
{{-- Role Permission Management UI --}}
@extends('layouts.admin')
@section('page-title', 'Role Permissions')
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Assign Permissions</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('roles.update-permissions') }}" method="POST">
            @csrf
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Module</th>
                        <th>Permissions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($modules as $module)
                        <tr>
                            <td>{{ ucfirst($module) }}</td>
                            <td>
                                @foreach($permissions->where('module', $module) as $permission)
                                    <label>
                                        <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" 
                                            {{ in_array($permission->id, $assignedPermissions) ? 'checked' : '' }}>
                                        {{ $permission->name }}
                                    </label>
                                @endforeach
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>
</div>
@endsection