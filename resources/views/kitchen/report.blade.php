@extends('layouts.admin')

@section('page-title', 'Vendor Service Purchase Order Report')

@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Kitchen Report</h3>
    </div>

    <div class="card-body">
       

        @if ($kitchenRecords->isNotEmpty())
            <div class="table-responsive mt-4">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Breakfast Count</th>
                            <th>Lunch Count</th>
                            <th>Dinner Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kitchenRecords as $record)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse( $record->date)->format('d-m-Y') }}</td>
                                <td>{{ $record->description }}</td>
                                <td>{{ $record->breakfast_count }}</td>
                                <td>{{ $record->lunch_count }}</td>
                                <td>{{ $record->dinner_count }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2"><strong>Total</strong></td>
                            <td><strong>{{ $kitchenRecords->sum('breakfast_count') }}</strong></td>
                            <td><strong>{{ $kitchenRecords->sum('lunch_count') }}</strong></td>
                            <td><strong>{{ $kitchenRecords->sum('dinner_count') }}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @else
            <div class="alert alert-warning mt-3">No Service Purchase Orders found for the selected date range.</div>
        @endif
    </div>
</div>

@endsection
