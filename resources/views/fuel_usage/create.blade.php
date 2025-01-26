@extends('layouts.admin')

@section('page-title', 'Add Fuel Usage')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3>Add Fuel Usage</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('fleet.fuel_usages.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="vehicle_id">Vehicle</label>
                    <select name="vehicle_id" id="vehicle_id" class="form-control" required>
                        <option value="">Select a Vehicle</option>
                        @foreach ($vehicles as $vehicle)
                            <option value="{{ $vehicle->id }}">{{ $vehicle->vehicle_number }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="fuel_amount">Fuel Amount (Liters)</label>
                    <input type="number" name="fuel_amount" id="fuel_amount" class="form-control" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="cost_per_liter">Cost per Liter</label>
                    <input type="number" name="cost_per_liter" id="cost_per_liter" class="form-control" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="date">Date</label>
                    <input type="date" name="date" id="date" class="form-control" required>
                </div>
                <div class="form-group text-right">
                    <button type="submit" class="btn btn-success">Add</button>
                    <a href="{{ route('fleet.fuel_usages.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
