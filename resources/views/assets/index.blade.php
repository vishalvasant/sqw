@extends('layouts.admin')

@section('page-title', 'Asset Management')

@section('content')
@php
    $usr = Auth::guard('web')->user();
@endphp
@if ($usr->can('reports.view'))
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Assets Report</h3>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('assets.reports') }}">
            <div class="row">
                <div class="col-md-3">
                    <label>From Date:</label>
                    <input type="date" name="from_date" class="form-control" value="{{ request()->from_date }}">
                </div>
                <div class="col-md-3">
                    <label>To Date:</label>
                    <input type="date" name="to_date" class="form-control" value="{{ request()->to_date }}">
                </div>
                <div class="col-md-3">
                    <label>Type:</label>
                    <select name="type" class="form-control">
                        <option value="parts" >Parts</option>
                        <option value="service">Service</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end justify-content-end">
                    <button type="submit" class="btn btn-success"><i class="fas fa-file-alt"></i>  Generate Report</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Assets</h3>
        @if ($usr->can('assets.create'))
        <a href="{{ route('assets.create') }}" class="btn btn-primary float-right"><i class="fas fa-plus-square"></i> Add New</a>
        @endif
    </div>
    <div class="card-body">
        <table class="table table-bordered" id="example1">
            <thead>
                <tr>
                    <th>Asset Name</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th width="10%">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($assets as $asset)
                    <tr>
                        <td>{{ $asset->asset_name }}</td>
                        <td>{{ $asset->description }}</td>
                        <td>
                            @if ($asset->status == 'active')
                                <span class="badge badge-success">{{ $asset->status }}</span>
                            @elseif ($asset->status == 'inactive')
                                <span class="badge badge-danger">{{ $asset->status }}</span>
                            @else
                                <span class="badge badge-warning">{{ $asset->status }}</span>
                            @endif
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Actions
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a href="{{ route('assets.show', $asset->id) }}" class="dropdown-item"><i class="fas fa-cog"></i> Part Allocate</a>
                                    <a href="{{ route('assets.serciceallocate', $asset->id) }}" class="dropdown-item"><i class="fas fa-recycle"></i> Service Allocate</a>
                                    <a href="{{ route('assets.parts.report', $asset->id) }}" class="dropdown-item"><i class="fas fa-file-alt"></i> Part Report</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

