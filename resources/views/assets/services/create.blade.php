@extends('layouts.admin')

@section('page-title', 'Allocate Parts to Asset')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Allocate Parts to Asset</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('assets.allocate', $asset->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <!-- Asset Selection -->
            <div class="form-group">
                <label for="asset">Asset</label>
                <select class="form-control" name="asset_id" required>
                    <option value="{{ $asset->id }}" selected>{{ $asset->name }}</option>
                    <!-- You can add more asset options if needed -->
                </select>
            </div>

            <!-- Product Selection with Quantity -->
            <div class="form-group">
                <label for="products">Select Products and Quantity</label>
                <div id="product-fields">
                    <div class="row product-row">
                        <div class="col-md-6">
                            <select class="form-control" name="products[0][product_id]" required>
                                <option value="">Select Product</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }} (Stock: {{ $product->stock }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="number" class="form-control" name="products[0][quantity]" placeholder="Quantity" min="1" required>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger remove-product-row" style="margin-top: 10px;">Remove</button>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-info" id="add-product-row" style="margin-top: 10px;">Add Another Product</button>
            </div>

            <button type="submit" class="btn btn-primary">Allocate Parts</button>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add new product row for allocation
        document.getElementById('add-product-row').addEventListener('click', function() {
            var productRowCount = document.querySelectorAll('.product-row').length;
            var newProductRow = `
                <div class="row product-row">
                    <div class="col-md-6">
                        <select class="form-control" name="products[${productRowCount}][product_id]" required>
                            <option value="">Select Product</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }} (Stock: {{ $product->stock }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="number" class="form-control" name="products[${productRowCount}][quantity]" placeholder="Quantity" min="1" required>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger remove-product-row" style="margin-top: 10px;">Remove</button>
                    </div>
                </div>
            `;
            document.getElementById('product-fields').insertAdjacentHTML('beforeend', newProductRow);
        });

        // Remove product row
        document.getElementById('product-fields').addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('remove-product-row')) {
                e.target.closest('.product-row').remove();
            }
        });
    });
</script>
@endsection
