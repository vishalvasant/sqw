@extends('layouts.admin')

@section('page-title', 'Add Category')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Add New Category</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('inventory.categories.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Category Name</label>
                <input type="text" class="form-control" id="name" name="category_name" required>
            </div>
            <button type="submit" class="btn btn-success">Save Category</button>
        </form>
    </div>
</div>
@endsection
