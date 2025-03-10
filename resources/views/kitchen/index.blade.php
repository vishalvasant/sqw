@extends('layouts.admin')

@section('page-title', 'Kitchen Records')
@php
    $usr = Auth::guard('web')->user();
@endphp
@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Kitchen Report</h3>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('kitchen.vendorReports') }}">
            <div class="row">
                <div class="col-md-3">
                    <label for="from_date">From Date</label>
                    <input type="date" name="from_date" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label for="to_date">To Date</label>
                    <input type="date" name="to_date" class="form-control" required>
                </div>
                <div class="col-md-3 d-flex align-items-end justify-content-end">
                    <button type="submit" class="btn btn-success"><i class="fas fa-file-alt"></i> Generate Report</button>
                </div>
            </div>
        </form>
    </div>
</div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Kitchen Records</h3>
            @if ($usr->can('kitchen.create'))
            <a href="{{ route('kitchen.create') }}" class="btn btn-primary float-right"><i class="fa fa-plus"></i> Add New</a>
            @endif
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
        
                    <table class="table table-bordered" id="example2">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Breakfast Count</th>
                                <th>Lunch Count</th>
                                <th>Dinner Count</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kitchenRecords as $record)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse( $record->date)->format('d-m-Y') }}</td>
                                    <td>{{ $record->breakfast_count }}</td>
                                    <td>{{ $record->lunch_count }}</td>
                                    <td>{{ $record->dinner_count }}</td>
                                    <td>{{ $record->description }}</td>
                                    <td>
                                        @if ($usr->can('kitchen.edit'))
                                        <a href="{{ route('kitchen.edit', $record->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        @endif
                                        @if ($usr->can('kitchen.destroy'))
                                        <form action="{{ route('kitchen.destroy', $record->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="id" value="{{ $record->id }}">
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
