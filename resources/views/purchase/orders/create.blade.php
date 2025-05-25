@extends('layouts.admin')

@section('page-title', 'Create Purchase Order')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Create Purchase Order</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('purchase.orders.store') }}" method="POST">
            @csrf
                <!-- Select Purchase Request -->
                <div class="row">
                    <div class="col-md-4">
                        <label for="gr_number">GR Number</label>
                        <input type="text" name="gr_number" id="gr_number" class="form-control" value="{{$gr_number}}" readonly>
                    </div>
                    <div class="col-md-8">
                        <label for="request_id">Purchase Request</label>
                        <select name="request_id" id="request_id" class="form-control" onchange="location.href='?request_id=' + this.value;">
                            <option value="">Select Purchase Request</option>
                            @foreach ($purchaseRequests as $pr)
                            @if($pr->status == 'approved')
                            <option value="{{ $pr->id }}" {{ request('request_id') == $pr->id ? 'selected' : '' }}>
                                {{ $pr->request_number }}
                            </option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                </div>
            
            

            <!-- Products from Purchase Request -->
            @if ($selectedRequest)
            <div class="row">
                <div class="col-md-6">
                    <!-- Select Supplier -->
                    <label for="supplier_id">Supplier</label>
                    <input type="text" name="supplier" id="supplier" class="form-control" value="{{$suppliers->name}}" readonly>
                    <input type="hidden" name="supplier_id" id="supplier_id" class="form-control" value="{{$suppliers->id}}" readonly>
                </div>
                <div class="col-md-3">
                    <label for="created_at">Date</label>
                    <input type="date" name="created_at" id="created_at" class="form-control" value="{{ old('created_at',\Carbon\Carbon::parse($selectedRequest->created_at)->format('Y-m-d')) }}">
                </div>
                <div class="col-md-3">
                    <label for="bill_no">Bill Number</label>
                    <input type="text" name="bill_no" id="bill_no" class="form-control" value="{{ old('bill_no') }}">
                </div>
            </div>

            <div class="form-group">
                <label for="billed">Is Billed?</label>
                <input type="checkbox" name="billed" id="billed" value="1" {{ old('billed') ? 'checked' : '' }}>
            </div>
            <h5>Products</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Requested Quantity</th>
                        <th>Quantity</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    
                    @foreach ($selectedRequest->items as $item)
                        <tr>
                            <td>{{ $item->product->name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>
                                <input type="number" name="products[{{ $loop->index }}][quantity]" class="form-control itemQty" value="{{ $item->quantity }}" step="0.01" required>
                                <input type="hidden" name="products[{{ $loop->index }}][purchase_request_item_id]" value="{{ $item->id }}">
                                <input type="hidden" name="products[{{ $loop->index }}][product_id]" value="{{ $item->product_id }}">
                            </td>
                            <td>
                                <input type="number" name="products[{{ $loop->index }}][price]"  value="{{ $item->price }}"  class="form-control itemPrice" step="0.01" required>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
            @endif

            <!-- Submit Button -->
            <div class="form-group text-right mt-4">
                <button type="submit" class="btn btn-success">Create Purchase Order</button>
                <a href="{{ route('purchase.orders.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        console.log("DOM loaded");
        document.querySelectorAll(".itemPrice").forEach(function (input) {
            input.addEventListener("input", function () {
            let maxPrice = parseFloat(input.getAttribute("value"));
            if (parseFloat(input.value) > maxPrice) {
                input.value = maxPrice;
                alert("Price cannot be greater than price:" + maxPrice);
            }
            });
        });

        document.querySelectorAll(".itemQty").forEach(function (input) {
            input.addEventListener("input", function () {
            let maxPrice = parseFloat(input.getAttribute("value"));
            if (parseFloat(input.value) > maxPrice) {
                input.value = maxPrice;
                alert("Qty cannot be greater than requested qty :  " + maxPrice);
            }
            });
        });
        
    });
</script>
@endsection
