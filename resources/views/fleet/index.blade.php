@extends('layouts.admin')

@section('page-title', 'Fleet Management')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Fleet Utilization Report</h3>
    </div>
    <div class="card-body">
        <!-- Date Range Filter for Utilization Report -->
        <form action="{{ route('fleet.utilization.report') }}" method="GET">
            <div class="row mb-4">
                <div class="col-md-4">
                    <label for="from_date">From Date:</label>
                    <input type="date" name="from_date" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label for="to_date">To Date:</label>
                    <input type="date" name="to_date" class="form-control" required>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">Generate Report</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Fleet</h3>
        <a href="{{ route('fleet.vehicles.create') }}" class="btn btn-primary float-right">Add New Vehicle</a>
    </div>
    <div class="card-body">
        

        <table class="table table-bordered" id="example2">
            <thead>
                <tr>
                    <th>Vehicle Number</th>
                    <th>Vehicle Type</th>
                    <th>Fixed Cost per Hour</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($vehicles as $vehicle)
                    <tr>
                        <td>{{ $vehicle->vehicle_number }}</td>
                        <td>{{ $vehicle->vehicle_type }}</td>
                        <td>{{ $vehicle->fixed_cost_per_hour }}</td>
                        <td>
                            <a href="{{ route('fleet.vehicles.utilization', $vehicle->id) }}" class="btn btn-info">
                                Fuel Utilization Report
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
