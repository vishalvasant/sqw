@extends('layouts.admin')

@section('page-title', 'Service Purchase Order Details')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
        <h3 class="card-title"><h3>Service Order #{{ $servicePurchaseOrder->order_number }}</h3></h3>
        </div>
        <div class="card-body">
            <p><strong>Vendor:</strong> {{ $servicePurchaseOrder->vendor->name}}</p>
            <p><strong>SO Date:</strong> {{ $servicePurchaseOrder->order_date }}</p>
            <p><strong>Status:</strong> <span class="badge badge-{{ $servicePurchaseOrder->status == 'approved' ? 'success' : 'warning' }}">{{ ucfirst($servicePurchaseOrder->status) }}</span></p>
            
            <h5>Services Order</h5>
            <table class="table">
                <thead>
                    <tr>
                        <th>Service</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($servicePurchaseOrder->items as $service)
                    <tr>
                        <td>{{ $service->service->name }}</td>
                        <td>{{ $service->quantity }}</td>
                        <td>{{ number_format($service->unit_price, 2) }}</td>
                        <td>{{ number_format($service->quantity * $service->unit_price, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <p><strong>Service Total:</strong> ₹{{ number_format($servicePurchaseOrder->items->sum(fn($service) => $service->quantity * $service->unit_price), 2) }}</p>

            <div class="form-group text-right mt-4">
                <a href="{{ route('service_po.index') }}" class="btn btn-secondary">Back to List</a>
            </div>
        </div>
    </div>
</div>
@endsection
