@extends('layouts.admin')

@section('page-title', 'Add Asset')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Add New Asset</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('assets.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="asset_name">Asset Name</label>
                <input type="text" class="form-control" name="asset_name" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" name="description"></textarea>
            </div>
            <div class="form-group">
                <label for="value">Value</label>
                <input type="number" class="form-control" name="value" required>
            </div>
            <div class="form-group">
                <label for="purchase_date">Purchase Date</label>
                <input type="date" class="form-control" name="purchase_date" required>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select class="form-control" name="status">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="maintenance">Under Maintenance</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Save Asset</button>
        </form>
    </div>
</div>
@endsection
