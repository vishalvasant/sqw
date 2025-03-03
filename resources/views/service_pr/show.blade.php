@extends('layouts.admin')

@section('page-title', 'Service Purchase Request Details')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><h3>Service Request #{{ $servicePurchaseRequest->request_number }}</h3></h3>
        </div>
        <div class="card-body">
            <p><strong>Vendor:</strong> {{ $servicePurchaseRequest->vendor->name }}</p>
            <p><strong>Request Date:</strong> {{ $servicePurchaseRequest->request_date }}</p>
            <p><strong>Description:</strong> {{ $servicePurchaseRequest->description }}</p>
            <p><strong>Status:</strong> 
                <span class="badge badge-{{ $servicePurchaseRequest->status == 'approved' ? 'success' : 'warning' }}">
                    {{ ucfirst($servicePurchaseRequest->status) }}
                </span>
            </p>
    
            <h5>Services Requested</h5>
            <table class="table">
                <thead>
                    <tr>
                        <th>Service</th>
                        <th>Quantity</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($servicePurchaseRequest->services as $service)
                    <tr>
                        <td>{{ $service->name }}</td>
                        <td>{{ $service->pivot->quantity }}</td>
                        <td>{{ $service->pivot->price }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <p><strong>Service Total:</strong> ₹{{ number_format($servicePurchaseRequest->services->sum(fn($service) => $service->pivot->quantity * $service->pivot->price), 2) }}</p>

            <div class="form-group text-right mt-4">
                <a href="{{ route('service_pr.index') }}" class="btn btn-secondary">Back to List</a>
            </div>
        </div>
</div>
</div>
@endsection
