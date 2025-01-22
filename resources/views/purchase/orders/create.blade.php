@extends('layouts.admin')

@section('page-title', 'Create Purchase Order')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Create New Purchase Order</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('purchase.orders.store') }}" method="POST">
            @csrf
            
            <!-- Purchase Request Selection -->
            <div class="form-group">
                <label for="purchase_request_id">Select Purchase Request (Optional)</label>
                <select name="purchase_request_id" id="purchase_request_id" class="form-control">
                    <option value="">-- Select Purchase Request --</option>
                    @foreach($purchaseRequests as $request)
                        <option value="{{ $request->id }}">{{ $request->id }} - {{ $request->status }}</option>
                    @endforeach
                </select>
            </div>

            <!-- If no Purchase Request, show the product addition section -->
            <div id="product-section" class="form-group">
                <h5>Or Add Products Manually</h5>
                <table id="product-table" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="products[0][product_id]" class="form-control">
                                    <option value="">-- Select Product --</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" name="products[0][quantity]" class="form-control" min="1" required></td>
                            <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
                        </tr>
                    </tbody>
                </table>
                <button type="button" class="btn btn-success" id="add-product">Add Product</button>
            </div>

            <div class="form-group">
                <label for="supplier_id">Select Supplier</label>
                <select name="supplier_id" id="supplier_id" class="form-control" required>
                    <option value="">-- Select Supplier --</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Create Purchase Order</button>
            <a href="{{ route('purchase.orders.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        var productIndex = 1;

        // Add a new product row
        $('#add-product').click(function() {
            var newRow = `
                <tr>
                    <td>
                        <select name="products[${productIndex}][product_id]" class="form-control">
                            <option value="">-- Select Product --</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="number" name="products[${productIndex}][quantity]" class="form-control" min="1" required></td>
                    <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
                </tr>
            `;
            $('#product-table tbody').append(newRow);
            productIndex++;
        });

        // Remove a product row
        $(document).on('click', '.remove-row', function() {
            $(this).closest('tr').remove();
        });
    });
</script>
@endsection
