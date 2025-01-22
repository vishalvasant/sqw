@extends('layouts.admin')

@section('page-title', 'Edit Unit')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Unit</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('inventory.units.update', $unit) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="unit_name">Unit Name</label>
                <input type="text" class="form-control" id="unit_name" name="unit_name" value="{{ $unit->unit_name }}" required>
            </div>
            <button type="submit" class="btn btn-success">Update Unit</button>
        </form>
    </div>
</div>
@endsection
