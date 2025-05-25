@extends('layouts.admin')

@section('page-title', 'Service Purchase Requests')
@php
    $usr = Auth::guard('web')->user();
@endphp
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">SR-SU Report</h3>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('service_pr.report') }}">
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
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Service Requests</h3>
        @if($usr->can('services_request.create'))
        <a href="{{ route('service_pr.create') }}" class="btn btn-primary float-right">Create SR</a>
        @endif
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Request Number</th>
                    <th>Vendor</th>
                    <th>Service</th>
                    <th>Requested By</th>
                    <th>Request Date</th>
                    <th>Status</th>
                    <th width="10%">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requests as $key => $request)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $request->request_number }}</td>
                    <td>{{ $request->vendor ? $request->vendor->name : 'N/A' }}</td>
                    <td>{{ $request->services[0]->name }}</td>
                    <td>{{ $request->requester->name }}</td>
                    <td>{{ $request->request_date }}</td>
                    <td>
                        <span class="badge badge-{{ $request->status == 'approved' ? 'success' : 'warning' }}">
                            {{ ucfirst($request->status) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('service_pr.show', $request->id) }}" class="btn btn-info btn-sm"><i class="fa fa-eye"></i></a>
                        @if($usr->can('services_request.edit'))
                        <a href="{{ route('service_pr.edit', $request->id) }}" class="btn btn-warning btn-sm"><i class="fa fa-pencil-alt"></i></a>
                        @endif
                        @if($usr->can('services_request.delete'))
                        <form action="{{ route('service_pr.destroy', $request->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i></button>
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
