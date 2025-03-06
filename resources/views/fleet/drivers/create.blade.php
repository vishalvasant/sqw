@extends('layouts.admin')

@section('page-title', 'Add New Driver')

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="card-title">Add New Driver</h4>
        <a href="{{ route('drivers.index') }}" class="btn btn-secondary float-right"><i class="fa fa-right-arrow"></i>Back</a>
    </div>
    <div class="card-body">
        <form action="{{ route('drivers.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Driver Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="license_number">License Number</label>
                <input type="text" name="license_number" id="license_number" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="contact_number">Contact Number</label>
                <input type="text" name="contact_number" id="contact_number" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="vehicle_id">Assign Vehicle</label>
                <select name="vehicle_id" id="vehicle_id" class="form-control">
                    <option value="">Select Vehicle</option>
                    @foreach ($vehicles as $vehicle)
                        <option value="{{ $vehicle->id }}">{{ $vehicle->vehicle_number }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="card-footer">
                <button type="submit" class="btn btn-primary">Save</button>
            </form>

        </div>
</div>
@endsection
