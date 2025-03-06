@extends('layouts.admin')

@section('page-title', 'Add New Driver')

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="card-title">Edit Driver</h4>
        <a href="{{ route('drivers.index') }}" class="btn btn-secondary float-right"><i class="fa fa-right-arrow"></i>Back</a>
    </div>
    <div class="card-body">
        <form action="{{ route('drivers.update', $driver->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name',$driver->name) }}" required>
            </div>

            <div class="form-group">
                <label for="license_number">License Number</label>
                <input type="text" name="license_number" id="license_number" class="form-control" value="{{old('license_numer',$driver->license_number)}}" required>
            </div>
            <div class="form-group">
                <label for="contact_number">Contact Number</label>
                <input type="text" name="contact_number" id="contact_number" class="form-control" value="{{old('contact_number',$driver->contact_number)}}" required>
            </div>
            <div class="form-group">
                <label for="vehicle_id">Assign Vehicle</label>
                <select name="vehicle_id" id="vehicle_id" class="form-control">
                    <option value="">Unassign</option>
                    @foreach ($vehicles as $vehicle)
                        <option value="{{ $vehicle->id }}" {{ $driver->vehicle_id == $vehicle->id ? 'selected' : '' }}>{{ $vehicle->vehicle_number }}</option>
                    @endforeach
                </select>
            
            </div>
        <div class="footer">
                <button type="submit" class="btn btn-primary">Update Driver</button>
            </form>        
        </div>
</div>
@endsection