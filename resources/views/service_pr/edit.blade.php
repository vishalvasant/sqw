@extends('layouts.admin')

@section('page-title', 'Edit Service Purchase Request')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Service PR</h3>
    </div>
    <form action="{{ route('service_pr.update', $servicePurchaseRequest->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="form-group">
                <label for="request_no">Request No</label>
                <input type="text" name="request_no" class="form-control" value="{{ $servicePurchaseRequest->request_number }}" required>
            </div>
            <div class="form-group">
                <label for="request_date">Request Date</label>
                <input type="date" name="request_date" class="form-control" value="{{ \Carbon\Carbon::parse($servicePurchaseRequest->request_date )->format('Y-m-d') }}" required>
            </div>
            <div class="form-group">
                <label for="vendor_id">Select Vendor</label>
                <select name="vendor_id" class="form-control" required>
                    @foreach($vendors as $vendor)
                        <option value="{{ $vendor->id }}" {{ $servicePurchaseRequest->vendor_id == $vendor->id ? 'selected' : '' }}>
                            {{ $vendor->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" class="form-control" required>
                    <option value="pending" {{ old('status', $servicePurchaseRequest->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ old('status', $servicePurchaseRequest->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ old('status', $servicePurchaseRequest->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div class="form-group">
                <label>Services Requested</label>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Service</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="service-table">
                        @foreach($servicePurchaseRequest->items as $index => $service)
                        <tr>
                        <td>
                                <input type="hidden" name="service[{{ $loop->index }}][id]" value="{{ $service->id }}">
                                <select 
                                    name="services[{{ $loop->index }}][service_id]" 
                                    class="form-control" 
                                    required>
                                    <option value="">Select Services</option>
                                    @foreach ($services as $product)
                                        <option 
                                            value="{{ $product->id }}" 
                                            {{ old("service.{$loop->index}.service_id", $service->service_id) == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="text" name="services[{{ $index }}][quantity]" class="form-control" value="{{ $service->quantity }}" required></td>
                            <td><input type="text" name="services[{{ $index }}][description]" class="form-control" value="{{ $service->description }}" required></td>
                            <td><button type="button" class="btn btn-danger btn-sm remove-row">Remove</button></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <button type="button" class="btn btn-secondary" id="add-service">Add Service</button>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Update PR</button>
            <a href="{{ route('service_pr.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<script>
    document.getElementById('add-service').addEventListener('click', function() {
        let index = document.querySelectorAll('#service-table tr').length;
        let row = `
            <tr>
                <td><input type="text" name="services[${index}][name]" class="form-control" required></td>
                <td><input type="number" name="services[${index}][quantity]" class="form-control" required></td>
                <td><input type="text" name="services[${index}][remarks]" class="form-control"></td>
                <td><button type="button" class="btn btn-danger btn-sm remove-row">Remove</button></td>
            </tr>`;
        document.getElementById('service-table').insertAdjacentHTML('beforeend', row);
    });

    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('remove-row')) {
            event.target.closest('tr').remove();
        }
    });
</script>
@endsection
