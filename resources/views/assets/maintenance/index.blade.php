@extends('layouts.admin')

@section('page-title', 'Allocate Maintance')

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="row">
            <div class="col-4">
                <h3 class="text-center">{{ $asset->asset_name }}</h3>

                <!-- Part Allocation Form -->
                <div class="card">
                    <div class="card-header">
                        <h5>Allocate New Maintance</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('assets.createMaintenance', $asset->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="text" name="asset_id" value="{{ $asset->id }}" hidden>
                            <div class="row mt-2">

                                    <label for="req_date">Request Date</label>
                                    <input type="date" name="req_date" id="req_date" class="form-control" placeholder="req_date"></input>

                            </div>
                            <div class="row mt-2">

                                    <label for="description">Description</label>
                                    <textarea name="description" id="description" class="form-control" placeholder="Description"></textarea>

                            </div>
                            <div class="row mt-2">
                                    <label for="req_by">Request By</label>
                                    <input type="hidden" name="asset_id" value="{{ $asset->id }}">
                                    <input type="text" name="req_by" id="req_by" class="form-control" placeholder="Request By">

                                    <label for="rec_by">Recived By</label>
                                    <input type="text" name="rec_by" id="rec_by" class="form-control" placeholder="Recived By">
                            </div>
                            <div class="row mt-2 float-right">
                                    <label for="exampleInputFile">Attach Log File</label>
                                    <input type="file" name="file" class="form-control">
                            </div>
                            <div class="row mt-4 float-right">
                                <button type="submit" class="btn btn-success">Allocate Maintance</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-8">
            <h3 class="text-center">Logs</h3>
                <div class="card">
                    <div class="card-header">
                        <h5>Allocated Maintance</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th>Request By</th>
                                    <th>Maintance By</th>
                                    <th>Log File</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($assetmaintenance as $maintance)
                                    <tr>
                                        <td>{{ $maintance->req_date }}</td>
                                        <td>{{ $maintance->description }}</td>
                                        <td>{{ $maintance->req_by }}</td>
                                        <td>{{ $maintance->rec_by }}</td>
                                        <td>
                                            @if ($maintance->file_path)
                                                <a href="{{ asset('storage/' .  $maintance->file_path) }}" target="_blank" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></a>
                                                <a href="{{ asset('storage/' . $maintance->file_path) }}" download class="btn btn-success btn-sm"><i class="fa fa-download"></i></a>
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('assets.destroyMaintenance', $asset->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="asset_id" value="{{ $asset->id }}">
                                                <input type="hidden" name="maintance_id" value="{{ $maintance->id }}">
                                                <button type="button" class="btn btn-danger" onclick="if(confirm('Are you sure you want to delete this?')) { this.form.submit(); }"><i class="fa fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


