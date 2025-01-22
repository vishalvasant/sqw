@extends('layouts.admin')

@section('page-title', 'Allocate New Part to Asset')

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="row">
            <div class="col-12">
                <h3 class="text-center">Allocate New Part to Asset: {{ $asset->name }}</h3>

                <!-- Part Allocation Form -->
                <div class="card">
                    <div class="card-header">
                        <h5>Add New Part</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('assets.parts.store', $asset->id) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="part_id">Part Name</label>
                                <select name="part_id" id="part_id" class="form-control" required>
                                    <option value="">Select a Part</option>
                                    @foreach($availableParts as $part)
                                        <option value="{{ $part->id }}">{{ $part->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="quantity">Quantity</label>
                                <input type="number" name="quantity" id="quantity" class="form-control" required placeholder="Enter quantity">
                            </div>
                            <button type="submit" class="btn btn-success">Allocate Part</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


