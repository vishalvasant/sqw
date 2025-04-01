@extends('layouts.admin')

@section('page-title', 'Product Services')
@php
    $usr = Auth::guard('web')->user();
@endphp
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Service Utilization Report</h3>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('inventory.products.report') }}">
            <div class="row">
                <div class="col-md-4">
                    <label for="from_date">From Date</label>
                    <input type="date" name="from_date" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label for="to_date">To Date</label>
                    <input type="date" name="to_date" class="form-control" required>
                </div>
                <div class="col-md-4 d-flex align-items-end justify-content-end">
                    <button type="submit" class="btn btn-success"><i class="fas fa-file-alt"></i> Generate Report</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="card">
    <div class="card-header">
        <h3 class="card-title"> Services</h3>
        @if($usr->can('services.create'))
            <a href="{{ route('product_services.create') }}" class="btn btn-primary float-right">Add Service</a>
        @endif
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered" id="example1">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Cost</th>
                    <th>Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($services as $service)
                    <tr>
                        <td>{{ $service->name }}</td>
                        <td>{{ $service->description }}</td>
                        <td>{{ $service->cost }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $service->type)) }}</td>
                        <td>
                            @if($usr->can('services.edit'))
                                <a href="{{ route('product_services.edit', $service->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            @endif
                            @if($usr->can('services.delete'))
                                <form action="{{ route('product_services.destroy', $service->id) }}" method="POST" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="if(confirm('Are you sure you want to delete this?')) { this.form.submit(); }">Delete</button>
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
