@extends('layouts.admin')

@section('page-title', 'Allocate New Part to Asset')

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="row">
            <div class="col-12">
                <h3 class="text-center">{{ $asset->asset_name }}</h3>

                <!-- Part Allocation Form -->
                <div class="card">
                    <div class="card-header">
                        <h5>Allocate New Part</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('assets.parts.allocate', $asset->id) }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-8">
                                    <label for="part_id">Part Name</label>
                                    <select name="part_id" id="part_id" class="form-control">
                                        <option value="">Select a Part</option>
                                            @foreach($availableParts as $part)
                                                <option value="{{ $part->id }}">{{ $part->name }}</option>
                                            @endforeach
                                    </select>
                                </div>
                                
                                <div class="col-md-4">
                                    <label for="quantity">Quantity</label>
                                    <input type="number" name="quantity" id="quantity" class="form-control" required placeholder="Enter quantity">
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <label for="req_by">Request By</label>
                                    <input type="text" name="req_by" id="req_by" class="form-control" placeholder="Request By">
                                </div>
                                <div class="col-md-6">
                                    <label for="rec_by">Recived By</label>
                                    <input type="text" name="rec_by" id="rec_by" class="form-control" placeholder="Recived By">
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row mt-2 float-right">
                                <button type="submit" class="btn btn-success">Allocate Part</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


