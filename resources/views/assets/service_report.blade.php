@extends('layouts.admin')

@section('page-title', 'Asset Part Utilization Report')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Service Utilization Report for {{ $asset->asset_name }}</h3>
    </div>
    <div class="card-body">
    <table class="table table-bordered" id="example1">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Product Name</th>
                    <th>Description</th>
                    <th>Request By</th>
                    <th>Recived By</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total = 0;
                @endphp
                @foreach($reportData as $index => $row)
                    @php
                        $total += $row->asset_price;
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($row->asset_service_created_at)->format('d-m-Y') }}</td>
                        <td>{{ $row->product_name }}</td>
                        <td>{{ $row->asset_service_description }}</td>
                        <td>{{ $row->req_by }}</td>
                        <td>{{ $row->rec_by }}</td>
                        <td>{{ number_format($row->asset_price, 2) }}</td>
                        <td>
                            <form action="{{ route('assets.services.remove') }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="asset_id" value="{{ $row->asset_id }}">
                                <input type="hidden" name="product_id" value="{{ $row->product_id }}">
                                <input type="hidden" name="asset_service_id" value="{{ $row->asset_service_id }}">
                                <input type="hidden" name="asset_order_number" value="{{ $row->asset_order_number }}">
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="5" class="text-right">Total:</th>
                    <th></th>
                    <th>{{ number_format($total, 2) }}</th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
        <a href="{{ route('assets.index') }}" class="btn btn-secondary mt-3">Back to Asset List</a>
    </div>
</div>
@endsection
