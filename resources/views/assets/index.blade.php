@extends('layouts.admin')

@section('page-title', 'Asset Management')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Assets</h3>
    </div>
    <div class="card-body">
        <a href="{{ route('assets.create') }}" class="btn btn-primary mb-3">Add New Asset</a>
        <table class="table table-bordered" id="example1">
            <thead>
                <tr>
                    <th>Asset Name</th>
                    <th>Description</th>
                    <th>Value</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($assets as $asset)
                    <tr>
                        <td>{{ $asset->asset_name }}</td>
                        <td>{{ $asset->description }}</td>
                        <td>{{ $asset->value }}</td>
                        <td>{{ $asset->status }}</td>
                        <td>
                            <a href="{{ route('assets.show', $asset->id) }}" class="btn btn-info">Allocate Part</a>
                            <a href="{{ route('assets.parts.report', $asset->id) }}" class="btn btn-success">Utilization Report</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

