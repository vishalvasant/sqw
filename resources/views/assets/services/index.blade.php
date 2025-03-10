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
                        <h5>Allocate New Service</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('assets.allocateservice') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-12 mb-2">
                                    <label for="part_id">Service Name</label>
                                    <select name="service_id" id="service_id" class="form-control" onchange="location.href='?request_id=' + this.value;">
                                        <option value="">Select a SO</option>
                                            @foreach($availableServices as $service)
                                                <option value="{{ $service->id }}" {{ request('request_id') == $service->id ? 'selected' : '' }}>{{ $service->order_number }} - {{ $service->items[0]->service->name }}</option>
                                            @endforeach
                                    </select>
                                </div>
                            </div>
                            @if(isset($selectedSO))
                            <div class="row mb-2">
                                <div class="col-md-3">
                                    <label for="service_date">SO Date</label>
                                    <input type="text" name="s_date" id="service_date" class="form-control" value="{{ \Carbon\Carbon::parse($selectedSO[0]->created_at)->format('d-m-Y') }}" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label for="service_id">SO ID</label>
                                    <input type="text" name="s_id" id="service_id" class="form-control" value="{{ $selectedSO[0]->order_number }}" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label for="service_name">SO Item Name</label>
                                    <input type="text" name="s_name" id="service_name" class="form-control" value="{{ $selectedSO[0]->items[0]->service->name }}" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label for="service_amount">SO Amount</label>
                                    <input type="text" name="s_amount" id="service_amount" class="form-control" value="{{ $selectedSO[0]->items[0]->total_price }}" readonly>
                                </div>
                            </div>
                            @endif
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <label for="description">Description</label>
                                    <textarea name="description" id="description" class="form-control" placeholder="Description"></textarea>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <label for="req_by">Request By</label>
                                    <input type="hidden" name="asset_id" value="{{ $asset->id }}">
                                    <input type="text" name="req_by" id="req_by" class="form-control" placeholder="Request By">
                                </div>
                                <div class="col-md-6">
                                    <label for="rec_by">Recived By</label>
                                    <input type="text" name="rec_by" id="rec_by" class="form-control" placeholder="Recived By">
                                </div>
                            </div>
                            <div class="row mt-2 float-right">
                                <button type="submit" class="btn btn-success">Allocate Service</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


