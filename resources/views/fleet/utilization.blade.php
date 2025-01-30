@extends('layouts.admin')

@section('page-title', 'Fuel Utilization Report')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Fuel Utilization Report for: {{ $vehicle->vehicle_number }}</h3>
    </div>
    <div class="card-body">
        <p><strong>Total Fuel Consumed:</strong> {{ $totalFuel }} Liters</p>
        <p><strong>Total Fuel Cost:</strong> {{ number_format($totalFuelCost, 2) }}</p>
        <p><strong>Average Cost per Liter:</strong> {{ number_format($avgCostPerLiter, 2) }}</p>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Fuel Amount (Liters)</th>
                    <th>Cost Per Liter</th>
                    <th>Total Cost</th>
                </tr>
            </thead>
            <tbody>
                @forelse($fuelUsages as $usage)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($usage->date)->format('d-m-Y') }}</td>
                        <td>{{ $usage->fuel_amount }}</td>
                        <td>{{ number_format($usage->cost_per_liter, 2) }}</td>
                        <td>{{ number_format($usage->total_cost, 2) }}</td>
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
