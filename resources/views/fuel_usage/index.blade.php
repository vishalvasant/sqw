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
        <table class="table table-bordered" id="example1">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Vehicle</th>
                    <th>Vehicle Type</th>
                    <th>Usage</th>
                    <th>Fuel (Liter)</th>
                    <th>Price</th>
                    <th>Avg. per Liter</th>
                    <th>Total Cost</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total = 0;
                    $total_cost = 0;
                @endphp
                @foreach ($fuelUsages as $usage)
                    @php
                        $total += $usage->fuel_amount;
                        $total_cost += $usage->fuel_amount * $usage->product->price;
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ \Carbon\Carbon::parse($usage->date)->format('d-m-Y')}}</td>
                        <td>{{ $usage->vehicle->vehicle_number }}</td>
                        <td>{{ $usage->vehicle->vehicle_type }}</td>
                        @if($usage->vehicle->vehicle_type == 'FIXED')   
                            <td>{{ $usage->hours_used  }} Hrs</td>
                        @elseif($usage->vehicle->vehicle_type == 'KMS')
                            <td>{{ $usage->distance_covered }} Kms</td>
                        @else
                            <td>{{ number_format($usage->distance_covered,0) }} Trips</td>
                        @endif
                        <td>{{ $usage->fuel_amount }}</td>
                        <td>{{ number_format($fulePrice,2) }}</td>
                        <td>{{ $usage->cost_per_liter }}</td>
                        <td>{{ number_format($usage->fuel_amount * $usage->product->price,2) }}</td>
                        <td>
                            @if ($usr->can('fuel_usages.delete'))
                            <!-- <a href="{{ route('fleet.fuel_usages.edit', $usage->id) }}" class="btn btn-warning btn-sm">Edit</a> -->
                            <form action="{{ route('fleet.fuel_usages.destroy', $usage->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"  onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="5" class="text-right">Total:</th>
                    <th>{{ $total }}</th>
                    <th></th>
                    <th></th>
                    <th>{{ $total_cost }}</th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
