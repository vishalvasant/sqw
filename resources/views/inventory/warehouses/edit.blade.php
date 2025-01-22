@extends('layouts.admin')

@section('page-title', 'Edit Warehouse')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Warehouse</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('warehouses.update', $warehouse) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="warehouse_name">Warehouse Name</label>
                <input type="text" class="form-control" id="warehouse_name" name="warehouse_name" value="{{ $warehouse->warehouse_name }}" required>
            </div>
            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" class="form-control" id="location" name="location" value="{{ $warehouse->location }}" required>
            </div>
            <button type="submit" class="btn btn-success">Update Warehouse</button>
        </form>
    </div>
</div>
@endsection
