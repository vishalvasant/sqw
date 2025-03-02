@extends('layouts.admin')

@section('page-title', 'Service Purchase Requests')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Service PR-PO Report</h3>
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
        <h3 class="card-title">Service Purchase Requests</h3>
        <a href="{{ route('service_pr.create') }}" class="btn btn-primary float-right">Create PR</a>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Request Number</th>
                    <th>Vendor</th>
                    <th>Requested By</th>
                    <th>Request Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requests as $key => $request)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $request->request_number }}</td>
                    <td>{{ $request->vendor ? $request->vendor->name : 'N/A' }}</td>
                    <td>{{ $request->requester->name }}</td>
                    <td>{{ $request->request_date }}</td>
                    <td>
                        <span class="badge badge-{{ $request->status == 'approved' ? 'success' : 'warning' }}">
                            {{ ucfirst($request->status) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('service_pr.show', $request->id) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('service_pr.edit', $request->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('service_pr.destroy', $request->id) }}" method="POST" style="display:inline;">
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
