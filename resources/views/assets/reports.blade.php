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
        @if(isset($assets) && count($assets) > 0)
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Asset Name</th>
                        <th>Product</th>
                        <th>Status</th>
                        <th>Purchase Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($assets as $index => $asset)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $asset->name }}</td>
                            <td>{{ $asset->product->name }}</td>
                            <td><span class="badge badge-info">{{ $asset->status }}</span></td>
                            <td>{{ $asset->purchase_date->format('d-m-Y') }}</td>
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
