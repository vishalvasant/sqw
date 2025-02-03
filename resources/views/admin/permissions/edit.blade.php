@extends('layouts.admin')

@section('page-title', 'Edit Permission')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Permission</h3>
    </div>
    <form action="{{ route('permissions.update', $permission->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="form-group">
                <label for="name">Permission Name</label>
                <input type="text" name="name" class="form-control" value="{{ $permission->name }}" required>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Update Permission</button>
        </div>
    </form>
</div>
@endsection
