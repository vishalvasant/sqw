@extends('layouts.admin')

@section('page-title', 'Product Utilization Report')

@section('content')
<div class="card">

<!-- Summary Section -->
    <div class="card-body">
        <h5>Report Summary ({{ request('from_date') }} to {{ request('to_date') }})</h5>
        <ul>
            
        </ul>
    </div>
    
    <!-- Report Table -->
    <div class="card-body">
        <table class="table table-bordered" id="example1">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Received Quantity</th>
                    <th>Average Price</th>
                    <th>Current Stock</th>
                    <th>Minimum Stock</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $totalR = 0;
                $totalS = 0;
                ?>
                @foreach($products as $data)
                <?php
                $totalR += $data->total_received;
                $totalS += $data->stock;
                ?>
                    <tr>
                        <td>{{ $data->name }}</td>
                        <td>{{ $data->total_received }}</td>
                        <td>{{ number_format($data->avg_price, 2) }}</td>
                        <td>{{ $data->stock }}</td>
                        <td>{{ $data->min_qty ?? 0 }}</td>
                    </tr>
                @endforeach
                <!-- <tr>
                    <td>-</td>
                    <td>Total Received: <?php echo $totalR;?></td>
                    <td>-</td>
                    <td>Total Stock: <?php echo $totalS;?></td>
                </tr> -->
            </tbody>
        </table>
    </div>
</div>
@endsection
