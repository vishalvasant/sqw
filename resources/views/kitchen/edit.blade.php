@extends('layouts.admin')

@section('page-title', 'Edit Kitchen Record')

@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Kitchen Records</h3>
        <a href="{{ route('kitchen.index') }}" class="btn btn-primary float-right"><i class="fa fa-arrow-left"></i> Back</a>
    </div>
    <div class="card-body">
        <form action="{{ route('kitchen.update', $kitchen->id) }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" value="{{ $kitchen->id }}">
            <div class="row">
                <div class="col-md-3">
                    <label>Date</label>
                    <input type="date" name="date" class="form-control" value="{{ old('date',$kitchen->date) }}" required>
                </div>
                <div class="col-md-3">
                    <label>Breakfast Count</label>
                    <input type="number" name="breakfast_count" class="form-control" value="{{ old('breakfast_count', $kitchen->breakfast_count) }}" required min="0">
                </div>
                <div class="col-md-3">
                    <label>Lunch Count</label>
                    <input type="number" name="lunch_count" class="form-control" value="{{ old('lunch_count',$kitchen->lunch_count) }}" required min="0">
                </div>
                <div class="col-md-3">
                    <label>Dinner Count</label>
                    <input type="number" name="dinner_count" class="form-control" value="{{ old('dinner_count',$kitchen->dinner_count) }}" required min="0">
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-12">
                    <label>Description</label>
                    <textarea name="description" class="form-control">{{ old('description',$kitchen->description) }}</textarea>
                </div>
            </div>
    </div>
    <div class="card-footer">
        <div class="row">
            <button type="submit" class="btn btn-success">Update</button>
        </form>
        </div>
    </div>
    </div>
</div>
@endsection
