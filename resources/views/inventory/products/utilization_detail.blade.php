@extends('layouts.admin')

@section('page-title', 'Product Utilization Report')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Utilization Report for: {{ $product->name }}</h3>
    </div>
    <div class="card-body">
        <p><strong>Total Quantity Received:</strong> {{ $totalQuantity }}</p>
        <p><strong>Average Price:</strong> {{ number_format($avgPrice, 2) }}</p>
        <p><strong>Current Stock:</strong> {{ $currentStock }}</p>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>PO Number</th>
                    <th>GRN Number</th>
                    <th>Supplier Name</th>
                    <th>Date Received</th>
                    <th>Quantity Received</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                @forelse($utilizationData as $data)
                    <tr>
                        <td>{{ $data->order_number }}</td>
                        <td>{{ $data->gr_number }}</td>
                        <td>{{ $data->name }}</td>
                        <td>{{ \Carbon\Carbon::parse($data->received_date)->format('d-m-Y') }}</td>
                        <td>{{ $data->quantity }}</td>
                        <td>{{ number_format($data->price, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No records found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
