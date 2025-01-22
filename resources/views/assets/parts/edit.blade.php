@extends('layouts.admin')

@section('page-title', 'Edit Asset Part')

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="row">
            <div class="col-12">
                <h3 class="text-center">Edit Part for Asset: {{ $asset->name }}</h3>

                <!-- Edit Part Form -->
                <div class="card">
                    <div class="card-header">
                        <h5>Edit Part</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('assets.parts.update', [$asset->id, $part->id]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="part_id">Part Name</label>
                                <select name="part_id" id="part_id" class="form-control" required>
                                    <option value="">Select a Part</option>
                                    @foreach($availableParts as $availablePart)
                                        <option value="{{ $availablePart->id }}" {{ $part->part_id == $availablePart->id ? 'selected' : '' }}>
                                            {{ $availablePart->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="quantity">Quantity</label>
                                <input type="number" name="quantity" id="quantity" class="form-control" value="{{ old('quantity', $part->quantity) }}" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Update Part</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



