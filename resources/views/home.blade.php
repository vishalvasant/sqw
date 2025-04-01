@extends('layouts.admin')
@php
    $usr = Auth::guard('web')->user();
@endphp
@section('content')
@if ($usr->can('dashboard.view') && $usr->can('dashboard.edit'))
<div class="row">
    <!-- Total Assets -->
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5>Total Assets</h5>
                <h3>{{ $totalAssets }}</h3>
            </div>
        </div>
    </div>
    
    <!-- Pending PRs -->
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h5>Pending Purchase Requests</h5>
                <h3>{{ $pendingPRs }}</h3>
            </div>
        </div>
    </div>

    <!-- Completed POs -->
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5>Completed Purchase Orders</h5>
                <h3>{{ $completedPOs }}</h3>
            </div>
        </div>
    </div>

    <!-- Total Users -->
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5>Total Users</h5>
                <h3>{{ $totalUsers }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Charts -->
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Purchase Orders Summary</div>
            <div class="card-body">
                <canvas id="poChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Purchase Requests Summary</div>
            <div class="card-body">
                <canvas id="prChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Latest PRs & POs -->
<div class="row">
    <!-- Recent Purchase Requests -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Recent Purchase Requests</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Requestor</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($latestPRs as $pr)
                        <tr>
                            <td>{{ $pr->request_number }}</td>
                            <td>{{ $pr->user->name }}</td>
                            <td>{{ $pr->created_at->format('d-m-Y') }}</td>
                            <td><span class="badge badge-{{ $pr->status == 'pending' ? 'warning' : 'success' }}">{{ $pr->status }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Purchase Orders -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Recent Purchase Orders</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Supplier</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($latestPOs as $po)
                        <tr>
                            <td>{{ $po->order_number }}</td>
                            <td>{{ $po->supplier->name }}</td>
                            <td>{{ $po->created_at->format('d-m-Y') }}</td>
                            <td><span class="badge badge-{{ $po->status == 'Pending' ? 'warning' : 'success' }}">{{ $po->status }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@else
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Dashboard</h3>
            </div>
            <div class="card-body">
                <p>Welcome to the dashboard.</p>
            </div>
        </div>
    </div>
@endif
@endsection


    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var poChartElement = document.getElementById('poChart');
            var prChartElement = document.getElementById('prChart');

            if (poChartElement) {
                var ctx1 = poChartElement.getContext('2d');
                var poChart = new Chart(ctx1, {
                    type: 'bar',
                    data: {
                        labels: @json($months),
                        datasets: [{
                            label: 'Purchase Orders',
                            data: @json($poCounts),
                            backgroundColor: 'rgba(54, 162, 235, 0.5)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    }
                });
            }

            if (prChartElement) {
                var ctx2 = prChartElement.getContext('2d');
                var prChart = new Chart(ctx2, {
                    type: 'bar',
                    data: {
                        labels: @json($months),
                        datasets: [{
                            label: 'Purchase Requests',
                            data: @json($prCounts),
                            backgroundColor: 'rgba(255, 99, 132, 0.5)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        }]
                    }
                });
            }
        });
    </script>
