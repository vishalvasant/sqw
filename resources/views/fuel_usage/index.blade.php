@extends('layouts.admin')

@section('page-title', 'Fuel Logs')

@section('content')
@php
    $usr = Auth::guard('web')->user();
@endphp

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Fuel Logs</h3>
        @if ($usr->can('fuel_usages.create'))
        <a href="{{ route('fleet.fuel_usages.create') }}" class="btn btn-primary float-right"><i class="fas fa-plus-square"></i> Add Log</a>
        @endif
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <table class="table table-bordered" id="example2">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Vehicle</th>
                    <th>Vehicle Type</th>
                    <th>Fuel (Liter)</th>
                    <th>Avg. per Liter</th>
                    <th>Total Cost</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($fuelUsages as $usage)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ \Carbon\Carbon::parse($usage->date)->format('d-m-Y')}}</td>
                        <td>{{ $usage->vehicle->vehicle_number }}</td>
                        <td>{{ $usage->vehicle->vehicle_type }}</td>
                        <td>{{ $usage->fuel_amount }}</td>
                        <td>{{ $usage->cost_per_liter }}</td>
                        <td>{{ $usage->fuel_amount * $usage->product->price }}</td>
                        <td>
                            @if ($usr->can('fuel_usages.delete'))
                            <!-- <a href="{{ route('fleet.fuel_usages.edit', $usage->id) }}" class="btn btn-warning btn-sm">Edit</a> -->
                            <form action="{{ route('fleet.fuel_usages.destroy', $usage->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
