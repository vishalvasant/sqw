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
                    <select name="vehicle_id" id="vehicle_id" class="form-control" required onchange="location.href='?request_id=' + this.value;">
                        <option value="">Select a Vehicle</option>
                        @foreach ($vehicles as $vehicle)
                            <option value="{{ $vehicle->id }}" {{ request('request_id') == $vehicle->id ? 'selected' : '' }}>{{ $vehicle->vehicle_number }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="product_id">Select Fuel Type</label>
                    <select name="product_id" class="form-control" required>
                        <option value="" disabled selected>Select Fuel</option>
                        @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
                @if($selectedRequest)
                    <div class="form-group">
                        <label for="vehicle_type">Vehicle Type</label>
                        <input type="text" name="vehicle_type" id="vehicle_type" value="{{ $selectedRequest->vehicle_type}}" class="form-control" readonly>
                    </div>
                    @if($selectedRequest->vehicle_type == 'KMS')
                        <div class="form-group">
                            <label for="odometer">Prev. Odometer</label>
                            <input type="text" name="odometer" id="odometer" value="{{ $selectedRequest->odometer}}" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label for="readings">New Reading</label>
                            <input type="number" name="readings" id="readings" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="kmsrun">KMS Runs</label>
                            <input type="number" name="kmsrun" id="kmsrun" class="form-control" readonly>
                        </div>
                    @elseif($selectedRequest->vehicle_type == 'TRIP')
                        <div class="form-group">
                            <label for="trip">Trip</label>
                            <input type="number" name="trip" id="trip" class="form-control">
                        </div>
                    @elseif($selectedRequest->vehicle_type == 'FIXED')
                        <div class="form-group">
                            <label for="hours_used">Hours</label>
                            <input type="number" name="hours_used" id="hours_used" class="form-control">
                        </div>
                    @endif
                        <div class="form-group">
                            <label for="fuel_amount">Fuel (Liters)</label>
                            <input type="number" name="fuel_amount" id="fuel_amount" class="form-control" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label for="cost_per_liter">Cost per Liter</label>
                            <input type="number" name="cost_per_liter" id="cost_per_liter" class="form-control" step="0.01" readonly>
                        </div>
                @endif
                
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
