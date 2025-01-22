@extends('layouts.admin')

@section('page-title', 'Categories')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Categories</h3>
        <a href="{{ route('inventory.categories.create') }}" class="btn btn-primary float-right">Add New Category</a>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Category Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                    <tr>
                        <td>{{ $category->id }}</td>
                        <td>{{ $category->category_name }}</td>
                        <td>
                            <a href="{{ route('inventory.categories.edit', $category) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('inventory.categories.destroy', $category) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
