@extends('layouts.admin')

@section('page-title', 'Vendors')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Vendor Report</h3>
    </div>
    <div class="card-body">
        <!-- Date Range Filter Form -->
        <form method="GET" action="{{ route('vendors.report') }}" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <label>Start Date</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label>End Date</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control" required>
                </div>
                <div class="col-md-2 ">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-primary btn-block float-right">Generate Report</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Vendor List</h3>
        <a href="{{ route('vendors.create') }}" class="btn btn-primary float-right">Add Vendor</a>
    </div>

    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($vendors as $vendor)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $vendor->name }}</td>
                        <td>{{ $vendor->email }}</td>
                        <td>{{ $vendor->phone }}</td>
                        <td>{{ $vendor->address }}</td>
                        <td>
                            <a href="{{ route('vendors.edit', $vendor->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('vendors.destroy', $vendor->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $vendors->links() }}
    </div>
</div>
@endsection
