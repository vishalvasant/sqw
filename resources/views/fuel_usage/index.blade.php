@extends('layouts.admin')

@section('page-title', 'Fuel Usages')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3>Fuel Usages</h3>
            <a href="{{ route('fleet.fuel_usages.create') }}" class="btn btn-primary">Add Fuel Usage</a>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Vehicle</th>
                        <th>Fuel Amount</th>
                        <th>Cost per Liter</th>
                        <th>Total Cost</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($fuelUsages as $usage)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $usage->vehicle->vehicle_number }}</td>
                            <td>{{ $usage->fuel_amount }}</td>
                            <td>{{ $usage->cost_per_liter }}</td>
                            <td>{{ $usage->total_cost }}</td>
                            <td>{{ $usage->date }}</td>
                            <td>
                                <!-- <a href="{{ route('fleet.fuel_usages.edit', $usage->id) }}" class="btn btn-warning btn-sm">Edit</a> -->
                                <form action="{{ route('fleet.fuel_usages.destroy', $usage->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
