@extends('layouts.admin')

@section('page-title', 'Add Unit')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Add New Unit</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('inventory.units.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="unit_name">Unit Name</label>
                <input type="text" class="form-control" id="unit_name" name="unit_name" required>
            </div>
            <button type="submit" class="btn btn-success">Save Unit</button>
        </form>
    </div>
</div>
@endsection
