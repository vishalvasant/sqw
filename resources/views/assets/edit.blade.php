@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Asset</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('assets.update', $asset->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Asset Name</label>
                <input type="text" class="form-control" id="asset_name" name="asset_name" value="{{ $asset->asset_name }}" required>
                <input type="hidden" name="id" value="{{ $asset->id }}">
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" required>{{ $asset->description }}</textarea>
            </div>

            <div class="form-group">
                <label for="value">Value</label>
                <input type="number" class="form-control" id="value" name="value" value="{{ $asset->value }}" step="0.01" required>
            </div>

            <div class="form-group">
                <label for="purchase_date">Purchase Date</label>
                <input type="date" class="form-control" id="purchase_date" name="purchase_date" value="{{ $asset->purchase_date }}" required>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="active" {{ $asset->status == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ $asset->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="maintenance" {{$asset->status == 'maintenance' ? 'selected' : ''}} >Under Maintenance</option>

                </select>
            </div>

            
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">Update Asset</button>
        </form>
    </div>
</div>
@endsection