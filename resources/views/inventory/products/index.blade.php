@extends('layouts.admin')

@section('page-title', 'Products')

@section('content')
@php
    $usr = Auth::guard('web')->user();
@endphp
@if ($usr->can('reports.view'))
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Product Utilization Report</h3>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('inventory.products.report') }}">
            <div class="row">
                <div class="col-md-4">
                    <label for="from_date">From Date</label>
                    <input type="date" name="from_date" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label for="to_date">To Date</label>
                    <input type="date" name="to_date" class="form-control" required>
                </div>
                <div class="col-md-4 d-flex align-items-end justify-content-end">
                    <button type="submit" class="btn btn-success"><i class="fas fa-file-alt"></i> Generate Report</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Products</h3>
        @if ($usr->can('products.create'))
        <a href="{{ route('inventory.products.create') }}" class="btn btn-primary float-right"><i class="fas fa-plus-square"></i> Add New</a>
        @endif
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
                    <th>Total Received</th>
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
                            @php
                                $totalReceived = \DB::table('purchase_order_items')
                                    ->where('product_id', $product->id)
                                    ->sum('quantity');
                            @endphp
                            {{ $totalReceived }}
                        </td>
                        <td>
                            @if ($usr->can('products.edit'))
                            <a href="{{ route('inventory.products.edit', $product) }}" class="btn btn-warning"><i class="fas fa-pencil-alt"></i></a>
                            @endif
                            @if ($usr->can('products.delete'))
                            <form action="{{ route('inventory.products.destroy', $product) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                            @endif
                            @if ($usr->can('products.view'))
                            <a href="{{ route('products.utilization.report', $product->id) }}" class="btn btn-info">Utilization</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
