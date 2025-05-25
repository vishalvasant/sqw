@extends('layouts.admin')

@section('page-title', 'Create Service Purchase Order')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Create Service Order</h3>
    </div>
    <form action="{{ route('service_po.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-2">
                    <label for="gr_number">SR Number</label>
                    <input type="text" name="gr_number" id="gr_number" class="form-control" value="{{$gr_number}}" readonly>
                </div>
                <div class="col-md-6">
                    <label for="service_pr_id">Select Service Request</label>
                    <select name="service_pr_id" class="form-control" onchange="location.href='?request_id=' + this.value;" required>
                            <option value="">Select Service Request</option>
                            @foreach ($purchaseRequests as $pr)
                            @if($pr->status == 'approved')
                            <option value="{{ $pr->id }}" {{ request('request_id') == $pr->id ? 'selected' : '' }}>
                                {{ $pr->request_number }}
                            </option>
                            @endif
                            @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="order_date">SO Date</label>
                    <input type="date" name="order_date" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <label for="bill_no">Bill Number</label>
                    <input type="text" name="bill_no" id="bill_no" class="form-control" value="{{ old('bill_no') }}">
                </div>
            </div>


            @if ($selectedRequest)
            <!-- Select Supplier -->
            <div class="form-group">
                <label for="supplier_id">Vendor</label>
                <input type="text" name="vendors" id="vendors" class="form-control" value="{{$vendors->name}}" readonly>
                <input type="hidden" name="vendors_id" id="vendors_id" class="form-control" value="{{$vendors->id}}" readonly>

            </div>

            <div class="form-group">
                <label for="billed">Is Billed?</label>
                <input type="checkbox" name="billed" id="billed" value="1" {{ old('billed') ? 'checked' : '' }}>
            </div>
            <h5>Services</h5>
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
                            <td>{{ $item->service->name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>
                                <input type="number" name="products[{{ $loop->index }}][quantity]" class="form-control" value="{{ $item->quantity }}" required>
                                <input type="hidden" name="products[{{ $loop->index }}][service_purchase_request_id]" value="{{ $item->id }}">
                                <input type="hidden" name="products[{{ $loop->index }}][service_id]" value="{{ $item->service_id }}">
                            </td>
                            <td>
                                <input type="number" name="products[{{ $loop->index }}][price]"  value="{{ $item->price }}"  class="form-control itemPrice"  required>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
            @endif
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Submit SO</button>
            <a href="{{ route('service_po.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".itemPrice").forEach(function (input) {
            input.addEventListener("input", function () {
            let maxPrice = parseFloat(input.getAttribute("value"));
            if (parseFloat(input.value) > maxPrice) {
                input.value = maxPrice;
                alert("Price cannot be greater than " + maxPrice);
            }
            });
        });
        document.addEventListener("input", function (event) {
            if (event.target.matches("[name^='services'][name$='[unit_price]']")) {
                let row = event.target.closest("tr");
                let quantity = parseFloat(row.cells[1].innerText);
                let unitPrice = parseFloat(event.target.value);
                row.querySelector(".total-price").innerText = (quantity * unitPrice).toFixed(2);
            }
        });
    });
</script>
@endsection
