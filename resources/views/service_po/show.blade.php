@extends('layouts.admin')

@section('page-title', 'Service Purchase Order Details')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">PO Details</h3>
    </div>
    <div class="card-body">
        <p><strong>PO Number:</strong> {{ $servicePurchaseOrder->po_number }}</p>
        <p><strong>Vendor:</strong> {{ $servicePurchaseOrder->vendor_id}}</p>
        <p><strong>PO Date:</strong> {{ $servicePurchaseOrder->order_date }}</p>
        <p><strong>Status:</strong> <span class="badge badge-{{ $servicePurchaseOrder->status == 'approved' ? 'success' : 'warning' }}">{{ ucfirst($servicePurchaseOrder->status) }}</span></p>
        
        <h4>Services Ordered</h4>
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
                    <td>{{ $service->name }}</td>
                    <td>{{ $service->pivot->quantity }}</td>
                    <td>{{ number_format($service->pivot->unit_price, 2) }}</td>
                    <td>{{ number_format($service->pivot->quantity * $service->pivot->unit_price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
