@extends('layouts.admin')

@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Product Ledger Report</h4>
        <button onclick="window.print()" class="btn btn-sm btn-primary">
            <i class="fas fa-print"></i> Print
        </button>
    </div>

    <div class="card-body">
        <!-- Report Header -->
        <div class="row mb-4">
            <div class="col-md-6">
                <p class="mb-1"><strong>Report Period:</strong> 
                    {{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} to {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}
                </p>
                @if($product)
                    <p class="mb-1"><strong>Product:</strong> {{ $product->name }}</p>
                    <p class="mb-1"><strong>Category:</strong> {{ $product->category->name ?? 'N/A' }}</p>
                    <p class="mb-1"><strong>Unit:</strong> {{ $product->unit->name ?? 'N/A' }}</p>
                @endif
            </div>
            <div class="col-md-6 text-md-end">
                <p class="mb-1"><strong>Generated On:</strong> {{ now()->format('M d, Y h:i A') }}</p>
                @if($vendor)
                    <p class="mb-1"><strong>Vendor:</strong> {{ $vendor->name }}</p>
                @endif
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <h6 class="card-title text-muted">Opening Stock</h6>
                        <h4>{{ number_format($openingStock, 2) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <h6 class="card-title text-muted">Received</h6>
                        <h4 class="text-success">+{{ number_format($totalReceived, 2) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <h6 class="card-title text-muted">Allocated</h6>
                        <h4 class="text-danger">-{{ number_format($totalAllocated, 2) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <h6 class="card-title">Closing Stock</h6>
                        <h4>{{ number_format($closingStock, 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Stock Movements -->
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Stock Movements</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>PO #</th>
                                    <th>Vendor</th>
                                    <th class="text-end">Qty</th>
                                    <th>Amount
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalAmount = $stockMovements->sum(function($movement) {
                                        return $movement->received_price * $movement->received_quantity;
                                    });
                                @endphp
                                @forelse($stockMovements as $movement)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($movement->purchase_date)->format('M d, Y') }}</td>
                                        <td>{{ $movement->order_number }}</td>
                                        <td>{{ $movement->vendor_name ?? 'N/A' }}</td>
                                        <td class="text-end">+{{ number_format($movement->received_quantity, 2) }}</td>
                                        <td class="text-end">{{ number_format(($movement->received_price*$movement->received_quantity), 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No stock movements found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if($stockMovements->isNotEmpty())
                                <tfoot>
                                    <tr class="table-active">
                                        <th colspan="3" class="text-end">Total:</th>
                                        <th class="text-end">{{ number_format($totalReceived, 2) }}</th>
                                        <th class="text-end">{{ number_format($totalAmount, 2) }}</th>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>

            <!-- Asset Allocations -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Asset Allocations</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Asset</th>
                                    <th class="text-end">Qty</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($assetAllocations as $allocation)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($allocation->allocation_date)->format('M d, Y') }}</td>
                                        <td>{{ $allocation->asset_name }}</td>
                                        <td class="text-end">-{{ number_format($allocation->quantity, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">No asset allocations found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if($assetAllocations->isNotEmpty())
                                <tfoot>
                                    <tr class="table-active">
                                        <th colspan="2" class="text-end">Total Allocated:</th>
                                        <th class="text-end">-{{ number_format($totalAllocated, 2) }}</th>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stock Summary -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Stock Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr class="table-light">
                                                <th>Opening Stock</th>
                                                <td class="text-end">{{ number_format($openingStock, 2) }}</td>
                                            </tr>
                                            <tr class="table-light">
                                                <th>+ Total Received</th>
                                                <td class="text-end text-success">+{{ number_format($totalReceived, 2) }}</td>
                                            </tr>
                                            <tr class="table-light">
                                                <th>- Total Allocated</th>
                                                <td class="text-end text-danger">-{{ number_format($totalAllocated, 2) }}</td>
                                            </tr>
                                            <tr class="table-secondary">
                                                <th>Calculated Closing</th>
                                                <th class="text-end">{{ number_format($openingStock + $totalReceived - $totalAllocated, 2) }}</th>
                                            </tr>
                                            <tr class="table-secondary">
                                                <th>Actual Closing</th>
                                                <th class="text-end">{{ number_format($closingStock, 2) }}</th>
                                            </tr>
                                            @php
                                                $difference = ($openingStock + $totalReceived - $totalAllocated) - $closingStock;
                                            @endphp
                                            @if($difference != 0)
                                            <tr class="table-{{ $difference > 0 ? 'danger' : 'success' }}">
                                                <th>Variance</th>
                                                <th class="text-end">
                                                    {{ $difference > 0 ? '+' : '' }}{{ number_format($difference, 2) }}
                                                    @if($product && $product->unit)
                                                        {{ $product->unit->name }}
                                                    @endif
                                                </th>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="card h-100">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">Stock Movement Overview</h6>
                                    </div>
                                    <div class="card-body d-flex align-items-center justify-content-center" style="min-height: 200px;">
                                        <div class="text-center">
                                            <div class="d-flex justify-content-around mb-3">
                                                <div class="text-center px-3">
                                                    <div class="h4 mb-0">{{ number_format($openingStock, 2) }}</div>
                                                    <small class="text-muted">Opening</small>
                                                </div>
                                                <div class="text-center px-3">
                                                    <div class="h4 text-success">+{{ number_format($totalReceived, 2) }}</div>
                                                    <small class="text-muted">Received</small>
                                                </div>
                                                <div class="text-center px-3">
                                                    <div class="h4 text-danger">-{{ number_format($totalAllocated, 2) }}</div>
                                                    <small class="text-muted">Allocated</small>
                                                </div>
                                                <div class="text-center px-3">
                                                    <div class="h4 text-primary">{{ number_format($closingStock, 2) }}</div>
                                                    <small class="text-muted">Closing</small>
                                                </div>
                                            </div>
                                            @if($difference != 0)
                                            <div class="alert alert-{{ $difference > 0 ? 'danger' : 'success' }} mb-0 py-2">
                                                <i class="fas fa-{{ $difference > 0 ? 'exclamation-triangle' : 'info-circle' }} me-2"></i>
                                                {{ $difference > 0 ? 'Inventory surplus' : 'Inventory shortage' }} of {{ number_format(abs($difference), 2) }}
                                                @if($product && $product->unit)
                                                    {{ $product->unit->name }}
                                                @endif
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card-footer text-muted text-center">
        <small>Report generated on {{ now()->format('M d, Y h:i A') }} | {{ config('app.name') }}</small>
    </div>
</div>
@push('styles')
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .card, .card * {
            visibility: visible;
        }
        .card {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            margin: 0;
            padding: 15px;
        }
        .no-print {
            display: none !important;
        }
        .table th, .table td {
            padding: 0.3rem;
        }
    }
</style>
@endpush

@push('scripts')
<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Stock Chart
        const ctx = document.getElementById('stockChart').getContext('2d');
        const stockChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Opening Stock', 'Received', 'Allocated', 'Closing Stock'],
                datasets: [{
                    label: 'Quantity',
                    data: [
                        {{ $openingStock }}, 
                        {{ $totalReceived }}, 
                        {{ -$totalAllocated }}, 
                        {{ $closingStock }}
                    ],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(153, 102, 255, 0.5)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Quantity'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y.toLocaleString(undefined, {maximumFractionDigits: 2});
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush

@endsection
