@extends('layouts.admin')

@section('page-title', 'Service Purchase Orders')

@section('content')
<div class="card">
        <div class="card-header">
            <h3 class="card-title">Service Allocation</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('service_po.report') }}">
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
        <h3 class="card-title">Service Orders</h3>
        <a href="{{ route('service_po.create') }}" class="btn btn-primary float-right">Create SO</a>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>SO Number</th>
                    <th>Vendor</th>
                    <th>SO Date</th>
                    <th>Status</th>
                    <th>Billed</th>
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
                        <form action="{{ route('service_po.updateStatus', $po->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <select 
                                name="status" 
                                class="form-control form-control-sm"
                                onchange="this.form.submit()"
                            >
                                <option value="pending" {{ $po->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="completed" {{ $po->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $po->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </form>
                    </td>
                    <td>
                        <form action="{{ route('service_po.updateBilled', $po->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <select 
                                name="billed" 
                                class="form-control form-control-sm"
                                onchange="this.form.submit()"
                            >
                                <option value="0" {{ !$po->billed ? 'selected' : '' }}>Unbilled</option>
                                <option value="1" {{ $po->billed ? 'selected' : '' }}>Billed</option>
                            </select>
                        </form>
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
