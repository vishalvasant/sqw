@extends('layouts.admin')

@section('page-title', 'Stock Import')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Stock Import</h3>
        <a href="{{ route('inventory.stock-imports.create') }}" class="btn btn-primary float-right">Import Stock</a>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Import Date</th>
                    <th>Total Products Imported</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($imports as $import)
                    <tr>
                        <td>{{ $import->id }}</td>
                        <td>{{ $import->created_at }}</td>
                        <td>{{ count($import->products) }}</td>
                        <td>
                            <a href="{{ route('stock-imports.show', $import) }}" class="btn btn-info">View</a>
                            <form action="{{ route('stock-imports.destroy', $import) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
