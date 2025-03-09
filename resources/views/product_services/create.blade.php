@extends('layouts.admin')

@section('page-title', 'Add Product Service')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Add New Service</h3>
    </div>
    <form action="{{ route('product_services.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label for="name">Service Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="description">Service Description</label>
                <textarea name="description" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <label for="cost">Cost</label>
                <input type="number" name="cost" class="form-control" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="type">Service Type</label>
                <select name="type" class="form-control" required>
                    <option value="asset_allocation">Asset Allocation</option>
                    <option value="expense">Expense</option>
                </select>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Add Service</button>
        </div>
    </form>
</div>
@endsection
