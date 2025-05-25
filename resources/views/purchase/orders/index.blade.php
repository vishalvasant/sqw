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
                    <div class="col-md-2">
                        <label>From Date:</label>
                        <input type="date" name="from_date" class="form-control" value="">
                    </div>
                    <div class="col-md-2">
                        <label>To Date:</label>
                        <input type="date" name="to_date" class="form-control" value="">
                    </div>
                    <div class="col-md-1">
                        <label>Status:</label>
                        <select name="status" class="form-control">
                            <option value="">All</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label>Billed:</label>
                        <select name="billed" class="form-control">
                            <option value="">All</option>
                            <option value="1">Billed</option>
                            <option value="0">Unbilled</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Supplier</label>
                            <select name="supplier_id[]" class="basic-multiple form-control" multiple="multiple" placeholder="Select Suppliers">
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                @endforeach
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
                        <th>GR Number</th>
                        <th>Bill Number</th>
                        <th>Date</th>
                        <th>Supplier</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Billed</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($purchaseOrders as $order)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $order->gr_number }}</td>
                            <td>{{ $order->bill_number ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d-m-Y') }}</td>
                            <td>{{ $order->supplier->name ?? 'N/A'  }}</td>
                            <td>₹{{ number_format($order->items->sum(fn($item) => $item->quantity * $item->price), 2) }}</td>
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
                                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }} disabled>Completed</option>
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
                                    @if ($order->status == 'completed')
                                    <button type="submit" class="btn btn-danger btn-sm" disabled>Delete</button>
                                    @else
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="if(confirm('Are you sure you want to delete this?')) { this.form.submit(); }">Delete</button>
                                    @endif
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
