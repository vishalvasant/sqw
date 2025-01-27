@extends('layouts.admin')

@section('page-title', 'Add New Vehicle')

@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Add New Asset</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('fleet.vehicles.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="vehicle_number">Vehicle Number</label>
                <input type="text" name="vehicle_number" id="vehicle_number" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="vehicle_type">Vehicle Type</label>
                <select class="form-control" name="vehicle_type" required>
                    <option value="KMS">Kilometer Wise</option>
                    <option value="TRIP">Trip Wise</option>
                    <option value="FIXED">Hourly Machine</option>
                </select>
            </div>
            <div class="form-group">
                <label for="odometer">Odometer Reading</label>
                <input type="text" name="odometer" id="odometer" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="value">Value</label>
                <input type="number" class="form-control" name="value">
            </div>
            <div class="form-group">
                <label for="purchase_date">Purchase Date</label>
                <input type="date" class="form-control" name="purchase_date">
            </div>
            <div class="form-group">
                <label for="fixed_cost_per_hour">Fixed Cost per Hour</label>
                <input type="number" name="fixed_cost_per_hour" id="fixed_cost_per_hour" class="form-control" step="0.01" require>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select class="form-control" name="status">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="maintenance">Under Maintenance</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
</div>
@endsection
