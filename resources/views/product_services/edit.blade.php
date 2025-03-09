@extends('layouts.admin')

@section('page-title', 'Edit Service')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Service</h3>
    </div>
    <form action="{{ route('product_services.update', $productService->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="card-body">
            <div class="form-group">
                <label for="name">Service Name</label>
                <input type="text" name="name" class="form-control" value="{{ $productService->name }}" required>
            </div>
            <div class="form-group">
                <label for="description">Service Description</label>
                <textarea name="description" class="form-control">{{ $productService->description }}</textarea>
            </div>
            <div class="form-group">
                <label for="cost">Cost</label>
                <input type="number" name="cost" class="form-control" value="{{ $productService->cost }}" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="type">Service Type</label>
                <select name="type" class="form-control" required>
                    <option value="asset_allocation" {{ $productService->type == "asset_allocation" ? "selected" : "" }}>Asset Allocation</option>
                    <option value="expense" {{ $productService->type == "expense" ? "selected" : "" }}>Expense</option>
                </select>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Update Service</button>
        </div>
    </form>
</div>
@endsection
