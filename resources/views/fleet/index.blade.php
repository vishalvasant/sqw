@extends('layouts.admin')

@section('page-title', 'Fleet Management')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Fleet</h3>
        <a href="{{ route('fleet.vehicles.create') }}" class="btn btn-primary float-right">Add New Vehicle</a>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
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
                            <a href="{{ route('fleet.edit', $vehicle->id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('fleet.destroy', $vehicle->id) }}" method="POST" style="display:inline;">
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
