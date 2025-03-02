@extends('layouts.admin')

@section('page-title', 'Stock-In Report')

@section('content')
<div class="card">

<!-- Summary Section -->
    <div class="card-body">
        <h5>Service Report Summary ({{ request('from_date') }} to {{ request('to_date') }})</h5>
        <ul>
            
        </ul>
    </div>
    
    <!-- Report Table -->
    <div class="card-body">
        @if(isset($purchaseOrders) && count($purchaseOrders) > 0)
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>SO ID</th>
                        <th>Vendor</th>
                        <th>Status</th>
                        <th>Billed</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($purchaseOrders as $index => $order)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $order->created_at->format('d-m-Y') }}</td>
                            <td>{{ $order->order_number }}</td>
                            <td>{{ $order->vendor->name }}</td>
                            <td><span class="badge badge-info">{{ $order->status }}</span></td>
                            <td>
                                @if($order->billed)
                                    <span class="badge badge-success">Billed</span>
                                @else
                                    <span class="badge badge-warning">Unbilled</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-center text-muted">No purchase orders found for the selected filters.</p>
        @endif
    </div>
</div>
@endsection
