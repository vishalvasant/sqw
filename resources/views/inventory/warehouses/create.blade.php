@extends('layouts.admin')

@section('page-title', 'Add Warehouse')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Add New Warehouse</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('warehouses.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="warehouse_name">Warehouse Name</label>
                <input type="text" class="form-control" id="warehouse_name" name="warehouse_name" required>
            </div>
            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" class="form-control" id="location" name="location" required>
            </div>
            <button type="submit" class="btn btn-success">Save Warehouse</button>
        </form>
    </div>
</div>
@endsection
