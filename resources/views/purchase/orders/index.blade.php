@extends('layouts.admin')

@section('page-title', 'Purchase Orders')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3>Purchase Orders</h3>
            <a href="{{ route('purchase.orders.create') }}" class="btn btn-primary">Create Purchase Order</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Order Number</th>
                        <th>Supplier</th>
                        <th>Status</th>
                        <th>Billed</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($purchaseOrders as $order)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $order->order_number }}</td>
                            <td>{{ $order->supplier->name ?? 'N/A'  }}</td>
                            <td>
                                <form action="{{ route('purchase.orders.updateStatus', $order->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <select 
                                        name="status" 
                                        class="form-control form-control-sm"
                                        onchange="this.form.submit()"
                                    >
                                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </form>
                            </td>
                            <td>
                                <form action="{{ route('purchase.orders.updateBilled', $order->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <select 
                                        name="billed" 
                                        class="form-control form-control-sm"
                                        onchange="this.form.submit()"
                                    >
                                        <option value="0" {{ !$order->billed ? 'selected' : '' }}>Unbilled</option>
                                        <option value="1" {{ $order->billed ? 'selected' : '' }}>Billed</option>
                                    </select>
                                </form>
                            </td>
                            <td>
                                <a href="{{ route('purchase.orders.show', $order->id) }}" class="btn btn-info btn-sm">View</a>
                                <form action="{{ route('purchase.orders.destroy', $order->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
