@extends('layouts.admin')

@section('page-title', 'Create Purchase Request')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Create Purchase Request</h3>
    </div>
    <form action="{{ route('purchase.requests.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" class="form-control" placeholder="Enter request title" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Enter request description"></textarea>
            </div>
            <div class="form-group">
                <label for="priority">Priority</label>
                <select name="priority" id="priority" class="form-control" required>
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                </select>
            </div>
            <div class="form-group">
                <label for="supplier_id">Supplier</label>
                <select name="supplier_id" id="supplier_id" class="form-control" required>
                    <option value="">Select Supplier</option>
                    @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="items">Request Items</label>
                <div id="items-container">
                    <div class="row mb-3 item-row">
                        <div class="col-md-6">
                            <select name="items[0][product_id]" class="form-control product-select" required>
                                <option value="" disabled selected>Select Product</option>
                                @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="items[0][price]" class="form-control price" placeholder="Price" required>
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="items[0][quantity]" class="form-control quantity" placeholder="Quantity" required>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-success add-item">Add</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subtotal & Total Section -->
            <div class="row">
                <div class="col-md-4 offset-md-8">
                    <div class="form-group">
                        <h5>Subtotal: ₹<span id="subtotal">0.00</span></h5>
                        <h4><strong>Total: ₹<span id="total">0.00</span></strong></h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let counter = 1;

    // Function to update subtotal & total
    function updateTotals() {
        let subtotal = 0;
        document.querySelectorAll('.item-row').forEach(row => {
            let price = parseFloat(row.querySelector('.price').value) || 0;
            let quantity = parseInt(row.querySelector('.quantity').value) || 0;
            subtotal += price * quantity;
        });
        document.getElementById('subtotal').innerText = subtotal.toFixed(2);
        document.getElementById('total').innerText = subtotal.toFixed(2);
    }

    // Add new item row
    document.querySelector('.add-item').addEventListener('click', function () {
        const container = document.getElementById('items-container');
        const newRow = `
            <div class="row mb-3 item-row">
                <div class="col-md-6">
                    <select name="items[${counter}][product_id]" class="form-control product-select" required>
                        <option value="" disabled selected>Select Product</option>
                        @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="number" name="items[${counter}][price]" class="form-control price" placeholder="Price" required>
                </div>
                <div class="col-md-3">
                    <input type="number" name="items[${counter}][quantity]" class="form-control quantity" placeholder="Quantity" required>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-danger remove-item">Remove</button>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', newRow);
        counter++;
    });

    // Remove item row
    document.getElementById('items-container').addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-item')) {
            e.target.closest('.row').remove();
            updateTotals();
        }
    });

    // Update totals on price/quantity change
    document.getElementById('items-container').addEventListener('input', function (e) {
        if (e.target.classList.contains('price') || e.target.classList.contains('quantity')) {
            updateTotals();
        }
    });
});
</script>
@endsection
