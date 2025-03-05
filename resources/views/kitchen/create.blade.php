@extends('layouts.admin')

@section('page-title', 'Add Kitchen Record')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Kitchen Records</h3>
        <a href="{{ route('kitchen.index') }}" class="btn btn-primary float-right"><i class="fa fa-arrow-left"></i> Back</a>
    </div>
    <div class="card-body">
        <form action="{{ route('kitchen.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-3">
                    <label>Date</label>
                    <input type="date" name="date" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label>Breakfast Count</label>
                    <input type="number" name="breakfast_count" class="form-control" required min="0">
                </div>
                <div class="col-md-3">
                    <label>Lunch Count</label>
                    <input type="number" name="lunch_count" class="form-control" required min="0">
                </div>
                <div class="col-md-3">
                    <label>Dinner Count</label>
                    <input type="number" name="dinner_count" class="form-control" required min="0">
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-12">
                    <label>Description</label>
                    <textarea name="description" class="form-control"></textarea>
                </div>
            </div>
                
    </div>
    <div class="card-footer">
        <div class="row">
            <button type="submit" class="btn btn-success">Save</button>
            </form>
        </div>
    </div>
</div>
@endsection
