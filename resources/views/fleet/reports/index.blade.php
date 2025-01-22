@extends('layouts.admin')

@section('page-title', 'Fleet Reports')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Fuel Usage Report</h3>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Vehicle</th>
                    <th>Fuel Amount</th>
                    <th>Cost per Liter</th>
                    <th>Total Cost</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($fuelUsageReport as $fuel)
                    <tr>
                        <td>{{ $fuel->vehicle->vehicle_number }}</td>
                        <td>{{ $fuel->fuel_amount }}</td>
                        <td>{{ $fuel->cost_per_liter }}</td>
                        <td>{{ $fuel->fuel_amount * $fuel->cost_per_liter }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

