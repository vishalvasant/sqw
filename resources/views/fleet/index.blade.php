@extends('layouts.admin')

@section('page-title', 'Fleet Management')

@section('content')
@php
    $usr = Auth::guard('web')->user();
@endphp

@if ($usr->can('reports.view'))
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Fleet Utilization Report</h3>
    </div>
    <div class="card-body">
        <!-- Date Range Filter for Utilization Report -->
        <form action="{{ route('fleet.utilization.report') }}" method="GET">
            <div class="row mb-4">
                <div class="col-md-4">
                    <label for="from_date">From Date:</label>
                    <input type="date" name="from_date" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label for="to_date">To Date:</label>
                    <input type="date" name="to_date" class="form-control" required>
                </div>
                <div class="col-md-4 d-flex align-items-end justify-content-end">
                    <button type="submit" class="btn btn-success float-right"><i class="fas fa-file-alt"></i> Generate Report</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Fleet</h3>
        @if ($usr->can('vehicles.create'))
        <a href="{{ route('vehicles.create') }}" class="btn btn-primary float-right"><i class="fas fa-plus-square"></i> Add New</a>
        @endif
    </div>
    <div class="card-body">
        

        <table class="table table-bordered" id="example2">
            <thead>
                <tr>
                    <th>Vehicle Number</th>
                    <th>Vehicle Type</th>
                    <th>Fixed Cost per Hour</th>
                    <th width="16%">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($vehicles as $vehicle)
                    <tr>
                        <td>{{ $vehicle->vehicle_number }}</td>
                        <td>{{ $vehicle->vehicle_type }}</td>
                        <td>{{ $vehicle->fixed_cost_per_hour }}</td>
                        <td>
                            @if ($usr->can('vehicles.edit'))
                            <a href="{{ route('vehicles.edit', $vehicle->id) }}" class="btn btn-warning"><i class="fa fa-pencil-alt"></i></a>
                            @endif
                            @if ($usr->can('vehicles.delete'))
                            <form action="{{ route('vehicles.destroy', $vehicle->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                @if(sizeof($vehicle->fuelUsages) > 0)
                                <button type="submit" class="btn btn-danger" disabled><i class="fa fa-trash"></i></button>
                                @else
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i></button>
                                @endif
                            </form>
                            @endif
                            @if ($usr->can('vehicles.edit'))
                            <a href="{{ route('fleet.vehicles.utilization', $vehicle->id) }}" class="btn btn-info"><i class="fa fa-file"></i> Fule Log</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
