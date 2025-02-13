@extends('layouts.admin')

@section('page-title', 'Suppliers')

@section('content')
@php
    $usr = Auth::guard('web')->user();
@endphp
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Suppliers</h3>
        @if ($usr->can('suppliers.create'))
        <a href="{{ route('purchase.suppliers.create') }}" class="btn btn-primary float-right"><i class="fas fa-plus-square"></i> Add New</a>
        @endif
    </div>
    <div class="card-body">
        <table class="table table-bordered" id="example1">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($suppliers as $supplier)
                <tr>
                    <td>{{ $supplier->id }}</td>
                    <td>{{ $supplier->name }}</td>
                    <td>{{ $supplier->email }}</td>
                    <td>{{ $supplier->phone }}</td>
                    <td>{{ $supplier->address }}</td>
                    <td>
                        @if ($usr->can('suppliers.edit'))
                        <a href="{{ route('purchase.suppliers.edit', $supplier) }}" class="btn btn-warning btn-sm">Edit</a>
                        @endif
                        @if ($usr->can('suppliers.delete'))
                        <form action="{{ route('purchase.suppliers.destroy', $supplier) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
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
@endsection
