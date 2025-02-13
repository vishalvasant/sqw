@extends('layouts.admin')

@section('page-title', 'Purchase Orders')

@section('content')
@php
    $usr = Auth::guard('web')->user();
@endphp
    @if ($usr->can('reports.view'))
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Stock-In Report</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('purchase.orders.report') }}">
                <div class="row">
                    <div class="col-md-3">
                        <label>From Date:</label>
                        <input type="date" name="from_date" class="form-control" value="{{ request()->from_date }}">
                    </div>
                    <div class="col-md-3">
                        <label>To Date:</label>
                        <input type="date" name="to_date" class="form-control" value="{{ request()->to_date }}">
                    </div>
                    <div class="col-md-2">
                        <label>Status:</label>
                        <select name="status" class="form-control">
                            <option value="">All</option>
                            <option value="Pending" {{ request()->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Approved" {{ request()->status == 'Approved' ? 'selected' : '' }}>Approved</option>
                            <option value="Completed" {{ request()->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Billed / Unbilled:</label>
                        <select name="billed" class="form-control">
                            <option value="">All</option>
                            <option value="1" {{ request()->billed == '1' ? 'selected' : '' }}>Billed</option>
                            <option value="0" {{ request()->billed == '0' ? 'selected' : '' }}>Unbilled</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end justify-content-end">
                        <button type="submit" class="btn btn-success"><i class="fas fa-file-alt"></i> Generate Report</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Stock-In</h3>
            @if ($usr->can('orders.create'))
            <a href="{{ route('purchase.orders.create') }}" class="btn btn-primary float-right"><i class="fas fa-plus-square"></i> Add New</a>
            @endif
        </div>
        <div class="card-body">
            <table class="table table-bordered" id="example1">
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
                                @if ($usr->can('orders.edit'))
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
                                @endif
                            </td>
                            <td>
                                @if ($usr->can('orders.edit'))
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
                                @endif
                            </td>
                            <td>
                                @if ($usr->can('orders.view'))
                                <a href="{{ route('purchase.orders.show', $order->id) }}" class="btn btn-info btn-sm">View</a>
                                @endif
                                @if ($usr->can('orders.delete'))
                                <form action="{{ route('purchase.orders.destroy', $order->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
