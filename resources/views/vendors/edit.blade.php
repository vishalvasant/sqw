@extends('layouts.admin')

@section('page-title', 'Edit Vendor')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Vendor</h3>
    </div>

    <form action="{{ route('vendors.update', $vendor->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card-body">
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" class="form-control" value="{{ $vendor->name }}" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="{{ $vendor->email }}">
            </div>

            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" class="form-control" value="{{ $vendor->phone }}">
            </div>

            <div class="form-group">
                <label>Address</label>
                <textarea name="address" class="form-control">{{ $vendor->address }}</textarea>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('vendors.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
