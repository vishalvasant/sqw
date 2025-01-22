@extends('layouts.admin')

@section('page-title', 'Add New Vehicle')

@section('content')
<form action="{{ route('fleet.vehicles.store') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="vehicle_number">Vehicle Number</label>
        <input type="text" name="vehicle_number" id="vehicle_number" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="vehicle_type">Vehicle Type</label>
        <input type="text" name="vehicle_type" id="vehicle_type" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="fixed_cost_per_hour">Fixed Cost per Hour</label>
        <input type="number" name="fixed_cost_per_hour" id="fixed_cost_per_hour" class="form-control" step="0.01" required>
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
</form>
@endsection
