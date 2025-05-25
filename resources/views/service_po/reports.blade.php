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
            <table class="table table-bordered" id="example1">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>SO ID</th>
                        <th>Service</th>
                        <th>Vendor</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalCost = 0;
                    @endphp
                    @foreach ($purchaseOrders as $index => $order)
                        @php
                            $totalCost += $order->service_purchase_order_items[0]->service->cost;
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $order->created_at->format('d-m-Y') }}</td>
                            <td>{{ $order->order_number }}</td>
                            <td>{{ $order->service_purchase_order_items[0]->service->name }}</td>
                            <td>{{ $order->vendor->name }}</td>
                            <!-- <td>{{ $order->service_purchase_order_items[0]->unit_price }}<br> -->
                            <td>₹{{ number_format($order->service_purchase_order_items->sum(fn($item) => $item->quantity * $item->unit_price), 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5" class="text-right">Total Cost:</th>
                        <th>{{ number_format($totalCost,2) }}</th>
                    </tr>
                </tfoot>
            </table>
        @else
            <p class="text-center text-muted">No purchase orders found for the selected filters.</p>
        @endif
    </div>
</div>
@endsection
