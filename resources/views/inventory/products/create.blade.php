@extends('layouts.admin')

@section('page-title', 'Add Product')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Add New Product</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('inventory.products.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="product_name">Product Name</label>
                <input type="text" class="form-control" id="product_name" name="name" required>
            </div>
            <div class="form-group">
                <label for="category_id">Category</label>
                <select class="form-control" id="category_id" name="category_id" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="unit_id">Unit</label>
                <select class="form-control" id="unit_id" name="unit_id" required>
                    @foreach($units as $unit)
                        <option value="{{ $unit->id }}">{{ $unit->unit_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="price">Price</label>
                <input type="number" class="form-control" id="price" name="price" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="stock_quantity">Stock Quantity</label>
                <input type="number" class="form-control" id="stock_quantity" name="stock" required>
            </div>
            <button type="submit" class="btn btn-success">Save Product</button>
        </form>
    </div>
</div>
@endsection
