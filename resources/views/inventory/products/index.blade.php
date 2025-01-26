@extends('layouts.admin')

@section('page-title', 'Products')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Products</h3>
        <a href="{{ route('inventory.products.create') }}" class="btn btn-primary float-right">Add New Product</a>
    </div>
    <div class="card-body">
        <table class="table table-bordered" id="example1">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Unit</th>
                    <th>Price</th>
                    <th>Stock Quantity</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->category->category_name }}</td>
                        <td>{{ $product->unit->unit_name }}</td>
                        <td>{{ $product->price }}</td>
                        <td>{{ $product->stock }}</td>
                        <td>
                            <a href="{{ route('inventory.products.edit', $product) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('inventory.products.destroy', $product) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
