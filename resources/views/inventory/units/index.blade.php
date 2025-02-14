@extends('layouts.admin')

@section('page-title', 'Units')

@section('content')
@php
    $usr = Auth::guard('web')->user();
@endphp
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Units</h3>
        @if ($usr->can('units.create'))
        <a href="{{ route('inventory.units.create') }}" class="btn btn-primary float-right"><i class="fas fa-plus-square"></i> Add New</a>
        @endif
    </div>
    <div class="card-body">
        <table class="table table-bordered" id="example2">
            <thead>
                <tr>
                    <th width="10%">#</th>
                    <th>Unit Name</th>
                    <th width="10%">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($units as $unit)
                    <tr>
                        <td>{{ $unit->id }}</td>
                        <td>{{ $unit->unit_name }}</td>
                        <td>
                            @if ($usr->can('units.edit'))
                            <a href="{{ route('inventory.units.edit', $unit) }}" class="btn btn-warning"><i class="fas fa-pencil-alt"></i></a>
                            @endif
                            @if ($usr->can('units.delete'))
                            <form action="{{ route('inventory.units.destroy', $unit) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i></button>
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
