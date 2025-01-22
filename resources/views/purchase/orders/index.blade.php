@extends('layouts.admin')

@section('page-title', 'Purchase Orders')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Purchase Orders</h3>
        <a href="{{ route('purchase.orders.create') }}" class="btn btn-primary float-right">Create New Order</a>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Order Number</th>
                    <th>Request Title</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($purchaseOrders as $order)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $order->order_number }}</td>
                    <td>{{ $order->purchaseRequest->title }}</td>
                    <td>{{ ucfirst($order->status) }}</td>
                    <td>
                        <a href="{{ route('purchase.orders.show', $order->id) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('purchase.orders.edit', $order->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('purchase.orders.destroy', $order->id) }}" method="POST" class="d-inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
