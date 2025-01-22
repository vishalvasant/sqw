@extends('layouts.admin')

@section('page-title', 'Edit Purchase Request')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3>Edit Purchase Request</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('purchase.requests.update', $purchaseRequest->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="title">Request Title</label>
                    <input 
                        type="text" 
                        name="title" 
                        id="title" 
                        class="form-control" 
                        value="{{ old('title', $purchaseRequest->title) }}" 
                        required>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea 
                        name="description" 
                        id="description" 
                        class="form-control" 
                        rows="4" 
                        required>{{ old('description', $purchaseRequest->description) }}</textarea>
                </div>

                <div class="form-group">
                    <label for="priority">Priority</label>
                    <select 
                        name="priority" 
                        id="priority" 
                        class="form-control" 
                        required>
                        <option value="low" {{ old('priority', $purchaseRequest->priority) == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('priority', $purchaseRequest->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ old('priority', $purchaseRequest->priority) == 'high' ? 'selected' : '' }}>High</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select 
                        name="status" 
                        id="status" 
                        class="form-control" 
                        required>
                        <option value="pending" {{ old('status', $purchaseRequest->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ old('status', $purchaseRequest->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ old('status', $purchaseRequest->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="supplier_id">Supplier</label>
                    <select 
                        name="supplier_id" 
                        id="supplier_id" 
                        class="form-control" 
                        required>
                        <option value="" disabled>Select Supplier</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ $purchaseRequest->supplier_id == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <hr>

                <h5>Products</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="product-list">
                        @foreach ($purchaseRequest->items as $item)
                            <tr>
                                <td>
                                    <input type="hidden" name="items[{{ $loop->index }}][id]" value="{{ $item->id }}">
                                    <select 
                                        name="items[{{ $loop->index }}][product_id]" 
                                        class="form-control" 
                                        required>
                                        <option value="">Select Product</option>
                                        @foreach ($products as $product)
                                            <option 
                                                value="{{ $product->id }}" 
                                                {{ old("products.{$loop->index}.product_id", $item->product_id) == $product->id ? 'selected' : '' }}>
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input 
                                        type="number" 
                                        name="items[{{ $loop->index }}][price]" 
                                        value="{{ old("products.{$loop->index}.price", $item->price) }}" 
                                        class="form-control" 
                                        required>
                                </td>
                                <td>
                                    <input 
                                        type="number" 
                                        name="items[{{ $loop->index }}][quantity]" 
                                        value="{{ old("products.{$loop->index}.quantity", $item->quantity) }}" 
                                        class="form-control" 
                                        required>
                                </td>
                               
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm remove-product">Remove</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <button type="button" id="add-product" class="btn btn-primary btn-sm">Add Product</button>

                <div class="form-group text-right mt-4">
                    <button type="submit" class="btn btn-success">Update</button>
                    <a href="{{ route('purchase.requests.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('add-product').addEventListener('click', function () {
        const productList = document.getElementById('product-list');
        const rowCount = productList.rows.length;
        const newRow = `
            <tr>
                <td>
                    <select name="items[${rowCount}][product_id]" class="form-control" required>
                        <option value="">Select Product</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="number" name="items[${rowCount}][price]" class="form-control" required>
                </td>
                <td>
                    <input type="number" name="items[${rowCount}][quantity]" class="form-control" required>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-product">Remove</button>
                </td>
            </tr>
        `;
        productList.insertAdjacentHTML('beforeend', newRow);
    });

    document.addEventListener('click', function (event) {
        if (event.target.classList.contains('remove-product')) {
            event.target.closest('tr').remove();
        }
    });
</script>
@endsection
