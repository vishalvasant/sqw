@extends('layouts.admin')

@section('page-title', 'Create Purchase Order')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3>Create Purchase Order</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('purchase.orders.store') }}" method="POST">
                @csrf
                 <!-- Select Purchase Request -->
                 <div class="form-group">
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
                
                

                <!-- Products from Purchase Request -->
                @if ($selectedRequest)
                <!-- Select Supplier -->
                <div class="form-group">
                    <label for="supplier_id">Supplier</label>
                    <input type="text" name="supplier" id="supplier" class="form-control" value="{{$suppliers->name}}" readonly>
                    <input type="hidden" name="supplier_id" id="supplier_id" class="form-control" value="{{$suppliers->id}}" readonly>

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
                                    <input type="number" name="products[{{ $loop->index }}][quantity]" class="form-control" value="{{ $item->quantity }}" required>
                                    <input type="hidden" name="products[{{ $loop->index }}][purchase_request_item_id]" value="{{ $item->id }}">
                                    <input type="hidden" name="products[{{ $loop->index }}][product_id]" value="{{ $item->product_id }}">
                                </td>
                                <td>
                                    <input type="number" name="products[{{ $loop->index }}][price]"  value="{{ $item->price }}"  class="form-control" required>
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
</div>
@endsection
