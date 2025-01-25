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
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
