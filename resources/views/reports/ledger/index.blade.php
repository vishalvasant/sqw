@extends('layouts.admin')

@section('content')
<!-- <div class="container"> -->
    <!-- <div class="row justify-content-center"> -->
        <!-- <div class="col-md-12"> -->
            <div class="card">
                <div class="card-header">
                    <h4>Product Ledger Report</h4>
                </div>

                <div class="card-body">
                    <form action="{{ route('reports.ledger.generate') }}" method="POST" target="_blank">
                        @csrf
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="product_id" class="form-label">Product</label>
                                <select name="product_id" id="product_id" class="form-select form-select-sm form-control">
                                    <option value="">All Products</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="vendor_id" class="form-label">Vendor</label>
                                <select name="vendor_id" id="vendor_id" class="form-select form-select-sm form-control">
                                    <option value="">All Vendors</option>
                                    @foreach($vendors as $vendor)
                                        <option value="{{ $vendor->id }}">
                                            {{ $vendor->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" name="start_date" id="start_date" class="form-control" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" name="end_date" id="end_date" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="reset" class="btn btn-secondary mr-2">Reset</button>
                            <button type="submit" class="btn btn-primary">Generate Report</button>
                        </div>
                    </form>
                </div>
            </div>
        <!-- </div> -->
    <!-- </div> -->
<!-- </div> -->

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set default date range (last 30 days)
        const endDate = new Date();
        const startDate = new Date();
        startDate.setDate(startDate.getDate() - 30);
        
        document.getElementById('start_date').valueAsDate = startDate;
        document.getElementById('end_date').valueAsDate = endDate;
        
        // Add date validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const startDate = new Date(document.getElementById('start_date').value);
            const endDate = new Date(document.getElementById('end_date').value);
            
            if (startDate > endDate) {
                e.preventDefault();
                alert('End date must be greater than or equal to start date');
            }
        });
    });
</script>
@endpush

@endsection
