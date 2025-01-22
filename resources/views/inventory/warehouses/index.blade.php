@extends('layouts.admin')

@section('page-title', 'Warehouses')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Warehouses</h3>
        <a href="{{ route('warehouses.create') }}" class="btn btn-primary float-right">Add New Warehouse</a>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Warehouse Name</th>
                    <th>Location</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($warehouses as $warehouse)
                    <tr>
                        <td>{{ $warehouse->id }}</td>
                        <td>{{ $warehouse->warehouse_name }}</td>
                        <td>{{ $warehouse->location }}</td>
                        <td>
                            <a href="{{ route('warehouses.edit', $warehouse) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('warehouses.destroy', $warehouse) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
