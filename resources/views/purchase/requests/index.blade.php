@extends('layouts.admin')

@section('page-title', 'Purchase Requests')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Purchase Requests</h3>
        <a href="{{ route('purchase.requests.create') }}" class="btn btn-primary float-right">Create New Request</a>
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
                    <th>Actions</th>
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
                        <a href="{{ route('purchase.requests.show', $request->id) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('purchase.requests.edit', $request->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('purchase.requests.destroy', $request->id) }}" method="POST" class="d-inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
