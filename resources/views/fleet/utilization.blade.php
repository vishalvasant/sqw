@extends('layouts.admin')

@section('page-title', 'Fuel Utilization Report')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Fuel Utilization Report for: {{ $vehicle->vehicle_number }}</h3>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-4">
                <div class="alert alert-info">
                    <h5>Total Fuel Consumed:</h5>
                    <strong>{{ number_format($totalFuel, 2) }} Liters</strong>
                </div>
            </div>
            <div class="col-md-4">
                <div class="alert alert-warning">
                    <h5>Total Cost:</h5>
                    <strong>₹{{ number_format(($totalFuel * $fulePrice), 2) }}</strong>
                </div>
            </div>
            <div class="col-md-4">
                <div class="alert alert-success">
                    <h5>Average Cost per Liter:</h5>
                    <strong>₹{{ number_format($totalFuel/$fulePrice, 2) }}</strong>
                </div>
            </div>
        </div>
        <!-- <p><strong>Total Fuel Consumed:</strong> {{ $totalFuel }} Liters</p>
        <p><strong>Total Fuel Cost:</strong> {{ number_format($totalFuelCost, 2) }}</p>
        <p><strong>Average Cost per Liter:</strong> {{ number_format($avgCostPerLiter, 2) }}</p> -->

        <table class="table table-bordered" id="example2">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Fuel Amount (Liters)</th>
                    <th>Fuel Price</th>
                    <th>Total Cost</th>
                </tr>
            </thead>
            <tbody>
                @forelse($fuelUsages as $usage)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($usage->date)->format('d-m-Y') }}</td>
                        <td>{{ $usage->fuel_amount }}</td>
                        <td>{{ number_format($fulePrice, 2) }}</td>
                        <td>{{ number_format($usage->cost_per_liter, 2) }}</td>
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
