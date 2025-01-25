@extends('layouts.admin')

@section('page-title', 'Purchase Order Details')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3>Purchase Order #{{ $order->order_number }}</h3>
        </div>
        <div class="card-body">
            <p><strong>Supplier:</strong> {{ $order->supplier->name }}</p>
            <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
            <p><strong>Billed:</strong> {{ $order->billed ? 'Yes' : 'No' }}</p>

            <h5>Products</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $item)
                        <tr>
                            <td>{{ $item->product->name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->price, 2) }}</td>
                            <td>{{ number_format($item->quantity * $item->price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <p><strong>Order Total:</strong> ₹{{ number_format($order->items->sum(fn($item) => $item->quantity * $item->price), 2) }}</p>

            <div class="form-group text-right mt-4">
                <a href="{{ route('purchase.orders.index') }}" class="btn btn-secondary">Back to List</a>
            </div>
        </div>
    </div>
</div>
@endsection
