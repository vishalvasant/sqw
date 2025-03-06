@extends('layouts.admin')

@section('page-title', 'Vendor Service Purchase Order Report')

@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Vendor Report (Service Purchase Orders)</h3>
    </div>

    <div class="card-body">
       

        @if ($servicePOs->isNotEmpty())
            <div class="table-responsive mt-4">
                <table class="table table-bordered" id="example2">
                    <thead>
                        <tr>
                            <th>SO Number</th>
                            <th>Vendor</th>
                            <th>Service</th>
                            <th>Type</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($servicePOs as $po)
                            <tr>
                                <td>{{ $po->order_number }}</td>
                                <td>{{ $po->vendor->name }}</td>
                                <td>{{ $po->items[0]->service->name ?? "N/A"}}</td>
                                <td>{{ ucwords(str_replace('_', ' ', $po->items[0]->service->type ?? "N/A")) }}</td>
                                <td>₹{{ number_format($po->items[0]['total_price'] ?? 0, 2) }}</td>
                                <td><span class="badge badge-{{ $po->status == 'approved' ? 'success' : 'warning' }}">{{ ucfirst($po->status) }}</span></td>
                                <td>{{ \Carbon\Carbon::parse($po->created_at)->format('d-m-Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-warning mt-3">No Service Purchase Orders found for the selected vendor.</div>
        @endif
    </div>
</div>

@endsection
