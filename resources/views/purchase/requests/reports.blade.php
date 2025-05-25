@extends('layouts.admin')

@section('page-title', 'PO-PR Report')

@section('content')
<div class="card">

<!-- Summary Section -->
    <div class="card-body">
        <h5>Purchase Request Summary ({{ request('from_date') }} to {{ request('to_date') }})</h5>
        <ul>
            
        </ul>
    </div>
    
    <!-- Report Table -->
    <div class="card-body">
        @if(isset($purchaseRequests) && count($purchaseRequests) > 0)
            <table class="table table-bordered" id="example2">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>PR-Number</th>
                        <th>Title</th>
                        <th>Requested By</th>
                        <th>Supplier</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($purchaseRequests as $index => $request)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $request->created_at->format('d-m-Y') }}</td>
                            <td>{{ $request->request_number }}</td>
                            <td>{{ $request->title }}</td>
                            <td>{{ $request->user->name }}</td>
                            <td>{{ $request->supplier->name }}</td>
                            @if($request->status == 'pending')
                            <td><span class="badge badge-info">{{ $request->status }}</span></td>
                            @elseif($request->status == 'approved')
                            <td><span class="badge badge-success">{{ $request->status }}</span></td>
                            @elseif($request->status == 'rejected')
                            <td><span class="badge badge-danger">{{ $request->status }}</span></td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-center text-muted">No purchase requests found for the selected date range.</p>
        @endif
    </div>
</div>
@endsection
