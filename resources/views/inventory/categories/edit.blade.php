@extends('layouts.admin')

@section('page-title', 'Edit Category')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Category</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('inventory.categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Category Name</label>
                <input type="text" class="form-control" id="name" name="category_name" value="{{ $category->category_name }}" required>
            </div>
            <button type="submit" class="btn btn-success">Update Category</button>
        </form>
    </div>
</div>
@endsection
