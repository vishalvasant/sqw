@extends('layouts.admin')

@section('page-title', 'Add Supplier')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Add New Supplier</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('purchase.suppliers.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control">
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" name="phone" id="phone" class="form-control">
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <textarea name="address" id="address" class="form-control"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('purchase.suppliers.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
