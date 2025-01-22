@extends('layouts.admin')

@section('page-title', 'Units')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Units</h3>
        <a href="{{ route('inventory.units.create') }}" class="btn btn-primary float-right">Add New Unit</a>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Unit Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($units as $unit)
                    <tr>
                        <td>{{ $unit->id }}</td>
                        <td>{{ $unit->unit_name }}</td>
                        <td>
                            <a href="{{ route('inventory.units.edit', $unit) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('inventory.units.destroy', $unit) }}" method="POST" style="display:inline;">
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
