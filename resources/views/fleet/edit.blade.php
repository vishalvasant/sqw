@extends('layouts.admin')

@section('page-title', 'Edit Vehicle')

@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Asset</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('vehicles.update', $vehicle->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="vehicle_number">Vehicle Number</label>
                <input type="text" name="vehicle_number" id="vehicle_number" class="form-control" value="{{ $vehicle->vehicle_number }}" required>
            </div>
            <div class="form-group">
                <label for="vehicle_type">Vehicle Type</label>
                <select class="form-control" name="vehicle_type" required>
                    <option value="KMS" {{ $vehicle->vehicle_type == 'KMS' ? 'selected' : '' }}>Kilometer Wise</option>
                    <option value="TRIP" {{ $vehicle->vehicle_type == 'TRIP' ? 'selected' : '' }}>Trip Wise</option>
                    <option value="FIXED" {{ $vehicle->vehicle_type == 'FIXED' ? 'selected' : '' }}>Hourly Machine</option>
                </select>
            </div>
            <div class="form-group">
                <label for="odometer">Odometer Reading</label>
                <input type="text" name="odometer" id="odometer" class="form-control" value="{{ $vehicle->odometer }}" required>
            </div>
            <div class="form-group">
                <label for="fixed_cost_per_hour">Fixed Cost per Hour</label>
                <input type="number" name="fixed_cost_per_hour" id="fixed_cost_per_hour" class="form-control" step="0.01" value="{{ $vehicle->fixed_cost_per_hour }}" required>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select class="form-control" name="status">
                    <option value="active" {{ $vehicle->status == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ $vehicle->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="maintenance" {{ $vehicle->status == 'maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</div>
@endsection