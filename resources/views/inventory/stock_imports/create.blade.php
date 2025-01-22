@extends('layouts.admin')

@section('page-title', 'Import Stock')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Import Stock</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('inventory.stock-imports.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="import_file">Select Import File (CSV or Excel)</label>
                <input type="file" class="form-control" id="import_file" name="import_file" accept=".csv, .xls, .xlsx" required>
            </div>
            <button type="submit" class="btn btn-success">Import</button>
        </form>
    </div>
</div>
@endsection
