@extends('layouts.admin')

@section('page-title', 'Stock Import Details')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Stock Import Details</h3>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Unit</th>
                    <th>Price</th>
                    <th>Stock Quantity</th>
                </tr>
            </thead>
            <tbody>
                @foreach($import->products as $product)
                    <tr>
                        <td>{{ $product->product_name }}</td>
                        <td>{{ $product->category->name }}</td>
                        <td>{{ $product->unit->unit_name }}</td>
                        <td>{{ $product->price }}</td>
                        <td>{{ $product->stock_quantity }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
