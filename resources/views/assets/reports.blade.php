@extends('layouts.admin')

@section('page-title', 'Assets Report')

@section('content')
<div class="card">

<!-- Summary Section -->
    <div class="card-body">
        <h5>Report Summary ({{ request('from_date') }} to {{ request('to_date') }})</h5>
        <ul>
            
        </ul>
    </div>
    
    <!-- Report Table -->
    <div class="card-body">
        @if(isset($reportData) && count($reportData) > 0)
        <table class="table table-bordered" id="example1">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Asset Name</th>
                    <th>Asset Status</th>
                    <th>Request By</th>
                    <th>Recived By</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reportData as $index => $row)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $row->asset_name }}</td>
                        <td>
                            @if ($row->asset_status == 'active')
                                <span class="badge badge-success">{{ $row->asset_status }}</span>
                            @elseif ($row->asset_status == 'inactive')
                                <span class="badge badge-danger">{{ $row->asset_status }}</span>
                            @else
                                <span class="badge badge-warning">{{ $row->asset_status }}</span>
                            @endif
                        </td>
                        <td>{{ $row->req_by }}</td>
                        <td>{{ $row->rec_by }}</td>
                        <td>{{ $row->product_name }}</td>
                        <td>{{ $row->product_quantity ?? 'N/A' }}</td>
                        <td>{{ number_format($row->avg_product_price, 2) }}</td>
                        <td>{{ number_format($row->avg_product_price * ($row->product_quantity ?? 1) , 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @else
            <p class="text-center text-muted">No assets found for the selected filters.</p>
        @endif
    </div>
</div>
@endsection
