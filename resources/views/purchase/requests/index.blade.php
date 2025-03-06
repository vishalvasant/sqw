@extends('layouts.admin')

@section('page-title', 'Purchase Requests')

@section('content')
@php
    $usr = Auth::guard('web')->user();
@endphp
@if ($usr->can('reports.view'))
<div class="card">
    <div class="card-header">
        <h3 class="card-title">PR-PO Report</h3>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('purchase.requests.report') }}">
            <div class="row">
                <div class="col-md-3">
                    <label for="from_date">From Date</label>
                    <input type="date" name="from_date" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label for="to_date">To Date</label>
                    <input type="date" name="to_date" class="form-control" required>
                </div>

                <div class="col-md-3">
                    <label for="to_date">Status</label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="all">All</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end justify-content-end">
                    <button type="submit" class="btn btn-success"><i class="fas fa-file-alt"></i> Generate Report</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Purchase Requests</h3>
        @if ($usr->can('requests.create'))
        <a href="{{ route('purchase.requests.create') }}" class="btn btn-primary float-right"><i class="fas fa-plus-square"></i> Add New</a>
        @endif
    </div>
    <div class="card-body">
        <table class="table table-bordered" id="example1">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Request Number</th>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Created By</th>
                    <th widht="10%">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($purchaseRequests as $request)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $request->request_number }}</td>
                    <td>{{ $request->title }}</td>
                    <td>{{ ucfirst($request->status) }}</td>
                    <td>{{ $request->user->name }}</td>
                    <td>
                        <!-- <a href="{{ route('purchase.requests.show', $request->id) }}" class="btn btn-sm btn-info">View</a> -->
                        @if ($usr->can('requests.edit'))
                        <a href="{{ route('purchase.requests.edit', $request->id) }}" class="btn btn-sm btn-warning"><i class="fas fa-pencil-alt"></i></a>
                        @endif
                        @if ($usr->can('requests.delete'))
                        <form action="{{ route('purchase.requests.destroy', $request->id) }}" method="POST" class="d-inline-block">
                            @csrf
                            @method('DELETE')
                            @if ($request->status != 'approved')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></button>
                            @else
                            <button type="submit" class="btn btn-sm btn-danger" disabled><i class="fas fa-trash"></i></button>
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
