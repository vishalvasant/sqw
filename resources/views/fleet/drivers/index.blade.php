@extends('layouts.admin')

@section('page-title', 'Drivers')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Drivers</h3>
        <a href="{{ route('fleet.drivers.create') }}" class="btn btn-primary float-right">Add New Driver</a>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Driver Name</th>
                    <th>License Number</th>
                    <th>Contact Number</th>
                    <th>Assigned Vehicle</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($drivers as $driver)
                    <tr>
                        <td>{{ $driver->name }}</td>
                        <td>{{ $driver->license_number }}</td>
                        <td>{{ $driver->contact_number }}</td>
                        <td>{{ $driver->vehicle ? $driver->vehicle->vehicle_number : 'Not Assigned' }}</td>
                        <td>
                            <a href="{{ route('fleet.drivers.edit', $driver->id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('fleet.drivers.destroy', $driver->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
