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
                <div class="col-md-4">
                    <label>From Date:</label>
                    <input type="date" name="from_date" class="form-control" value="{{ request()->from_date }}">
                </div>
                <div class="col-md-4">
                    <label>To Date:</label>
                    <input type="date" name="to_date" class="form-control" value="{{ request()->to_date }}">
                </div>
                <div class="col-md-4 d-flex align-items-end justify-content-end">
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
                    <th>Value</th>
                    <th>Status</th>
                    <th width="20%">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($assets as $asset)
                    <tr>
                        <td>{{ $asset->asset_name }}</td>
                        <td>{{ $asset->description }}</td>
                        <td>{{ $asset->value }}</td>
                        <td>
                            @if ($asset->status == 'active')
                                <span class="badge badge-success">{{ $asset->status }}</span>
                            @elseif ($asset->status == 'inactive')
                                <span class="badge badge-danger">{{ $asset->status }}</span>
                            @else
                                <span class="badge badge-warning">{{ $asset->status }}</span>
                            @endif
                        <td>
                            @if ($usr->can('parts.edit'))
                            <a href="{{ route('assets.show', $asset->id) }}" class="btn btn-info"><i class="fas fa-recycle"></i> Allocate</a>
                            @endif
                            @if ($usr->can('parts.view'))
                            <a href="{{ route('assets.parts.report', $asset->id) }}" class="btn btn-success"><i class="fas fa-file-alt"></i> Report</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

