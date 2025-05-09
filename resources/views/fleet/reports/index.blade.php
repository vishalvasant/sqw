@extends('layouts.admin')

@section('page-title', 'Fleet Reports')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Fuel Usage Report</h3>
    </div>
    <div class="card-body">
        <table class="table table-bordered" id="example1">
            <thead>
                <tr>
                    <th>Vehicle</th>
                    <th>Vehicle Type</th>
                    <th>Usage</th>
                    <th>Fuel Price</th>
                    <th>Cost per Liter</th>
                    <th>Total Cost</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($fuelUsageReport as $fuel)
                    <tr>
                        <td>{{ $fuel->vehicle->vehicle_number }}</td>
                        <td>{{ $fuel->vehicle->vehicle_type }}</td>
                        <td>{{ $fuel->fuel_amount }}</td>
                        <td>{{ number_format($fulePrice,2) }}</td>
                        <td>{{ $fuel->cost_per_liter }}</td>
                        <td>{{ number_format($fuel->fuel_amount * $fulePrice,2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

