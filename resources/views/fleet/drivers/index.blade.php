@extends('layouts.admin')

@section('page-title', 'Drivers')

@section('content')
@php
    $usr = Auth::guard('web')->user();
@endphp
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Drivers</h3>
        @if ($usr->can('drivers.create'))
        <a href="{{ route('drivers.create') }}" class="btn btn-primary float-right"><i class="fas fa-plus-square"></i> Add New</a>
        @endif
    </div>
    <div class="card-body">
        <table class="table table-bordered" id="example2">
            <thead>
                <tr>
                    <th>Driver Name</th>
                    <th>License Number</th>
                    <th>Contact Number</th>
                    <th>Assigned Vehicle</th>
                    <th width="10%">Actions</th>
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
                            @if ($usr->can('drivers.edit'))
                            <a href="{{ route('drivers.edit', $driver->id) }}" class="btn btn-warning"><i class="fas fa-pencil-alt"></i></a>
                            @endif
                            @if ($usr->can('drivers.delete'))
                            <form action="{{ route('drivers.destroy', $driver->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                @if(isset($driver->vehicle->vehicle_number))
                                <button type="submit" class="btn btn-danger" disabled><i class="fas fa-trash"></i></button>
                                @else
                                <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i></button>
                                @endif
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
