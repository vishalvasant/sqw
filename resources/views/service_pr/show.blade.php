@extends('layouts.admin')

@section('page-title', 'Service Purchase Request Details')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">PR Details</h3>
    </div>
    <div class="card-body">
        <p><strong>PR Number:</strong> {{ $servicePurchaseRequest->request_number }}</p>
        <p><strong>Vendor:</strong> {{ $servicePurchaseRequest->vendor_id }}</p>
        <p><strong>Request Date:</strong> {{ $servicePurchaseRequest->request_date }}</p>
        <p><strong>Status:</strong> 
            <span class="badge badge-{{ $servicePurchaseRequest->status == 'approved' ? 'success' : 'warning' }}">
                {{ ucfirst($servicePurchaseRequest->status) }}
            </span>
        </p>

        <h4>Services Requested</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Service</th>
                    <th>Quantity</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                @foreach($servicePurchaseRequest->items as $service)
                <tr>
                    <td>{{ $service->name }}</td>
                    <td>{{ $service->pivot->quantity }}</td>
                    <td>{{ $service->pivot->remarks }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
