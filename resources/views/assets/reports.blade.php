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
                    <th>Request By</th>
                    <th>Recived By</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    @if($_REQUEST['type'] == 'parts')
                        <th>Price</th>
                        <th>Total</th>
                    @else    
                        <th>Order Number</th>
                        <th>Price</th>
                        <th>Action</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($reportData as $index => $row)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $row->asset_name }} 
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
                        @if($_REQUEST['type'] == 'parts')
                            <td>{{ number_format($row->avg_product_price, 2) }}</td>
                            <td>{{ number_format($row->avg_product_price * ($row->product_quantity ?? 1) , 2) }}</td>
                        @else
                            <td>{{ $row->asset_order_number }}</td>
                            <td>{{ number_format($row->asset_price, 2) }}</td>
                            <td>
                                <form action="{{ route('assets.services.remove') }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="asset_id" value="{{ $row->asset_id }}">
                                    <input type="hidden" name="asset_service_id" value="{{ $row->asset_service_id }}">
                                    <input type="hidden" name="asset_order_number" value="{{ $row->asset_order_number }}">
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this asset?')"><i class="fas fa-trash"></i> Delete</button>
                                </form>
                            </td>
                        @endif
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
