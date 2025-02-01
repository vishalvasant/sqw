@extends('layouts.admin')

@section('page-title', 'Fleet Utilization Report')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Fuel Utilization Report ({{ $fromDate }} to {{ $toDate }})</h3>
    </div>
    <div class="card-body">
        <!-- Summary Section -->
        <div class="row mb-3">
            <div class="col-md-4">
                <div class="alert alert-info">
                    <h5>Total Fuel Used:</h5>
                    <strong>{{ number_format($totalFuel, 2) }} Liters</strong>
                </div>
            </div>
            <div class="col-md-4">
                <div class="alert alert-warning">
                    <h5>Total Cost:</h5>
                    <strong>₹{{ number_format($totalCost, 2) }}</strong>
                </div>
            </div>
            <div class="col-md-4">
                <div class="alert alert-success">
                    <h5>Average Cost per Liter:</h5>
                    <strong>₹{{ number_format($avgCostPerLiter, 2) }}</strong>
                </div>
            </div>
        </div>

        <!-- Detailed Report Table -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Vehicle Number</th>
                    <th>Vehicle Type</th>
                    <th>Total Fuel Used (Liters)</th>
                    <th>Utilization</th>
                    <th>Total Cost</th>
                    <th>Average Cost per Liter</th>
                </tr>
            </thead>
            <tbody>
                @forelse($fuelUsages as $usage)
                    <tr>
                        <td>{{ $usage->vehicle_number }}</td>
                        <td>{{ $usage->vehicle_type }}</td>
                        <td>{{ number_format($usage->total_fuel, 2) }}</td>
                        @if($usage->vehicle_type == 'FIXED')
                        <td>{{ number_format($usage->total_hours, 2) }} Hrs</td>
                        @elseif($usage->vehicle_type == 'TRIP')
                        <td>{{ number_format($usage->total_distance, 2) }} Trips</td>
                        @else
                        <td>{{ number_format($usage->total_distance, 2) }} Kms</td>
                        @endif
                        <td>₹{{ number_format($usage->total_cost, 2) }}</td>
                        <td>₹{{ number_format($usage->avg_cost_per_liter, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No records found for the selected date range.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
