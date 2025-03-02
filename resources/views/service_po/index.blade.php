@extends('layouts.admin')

@section('page-title', 'Service Purchase Orders')

@section('content')
<div class="card">
        <div class="card-header">
            <h3 class="card-title">Service Allocation</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('purchase.orders.report') }}">
                <div class="row">
                    <div class="col-md-3">
                        <label>From Date:</label>
                        <input type="date" name="from_date" class="form-control" value="">
                    </div>
                    <div class="col-md-3">
                        <label>To Date:</label>
                        <input type="date" name="to_date" class="form-control" value="">
                    </div>
                    <div class="col-md-2">
                        <label>Status:</label>
                        <select name="status" class="form-control">
                            <option value="">All</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Billed / Unbilled:</label>
                        <select name="billed" class="form-control">
                            <option value="">All</option>
                            <option value="1">Billed</option>
                            <option value="0">Unbilled</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end justify-content-end">
                        <button type="submit" class="btn btn-success"><i class="fas fa-file-alt"></i> Generate Report</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Service Purchase Orders</h3>
        <a href="{{ route('service_po.create') }}" class="btn btn-primary float-right">Create PO</a>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>PO Number</th>
                    <th>Vendor</th>
                    <th>PO Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $key => $po)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $po->order_number }}</td>
                    <td>{{ $po->vendor ? $po->vendor->name : 'N/A' }}</td>
                    <td>{{ $po->order_date   }}</td>
                    <td>
                        <span class="badge badge-{{ $po->status == 'approved' ? 'success' : 'warning' }}">
                            {{ ucfirst($po->status) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('service_po.show', $po->id) }}" class="btn btn-info btn-sm">View</a>
                        <form action="{{ route('service_po.destroy', $po->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
