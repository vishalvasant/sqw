@extends('layouts.admin')

@section('page-title', 'Service Reports')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Service Reports</h3>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('service_report.index') }}">
            <div class="row">
                <div class="col-md-4">
                    <label>Start Date</label>
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-4">
                    <label>End Date</label>
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-4">
                    <br>
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </div>
        </form>

        <h4 class="mt-4">Purchase Requests (PR)</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>PR Number</th>
                    <th>Vendor</th>
                    <th>Request Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($servicePRs as $pr)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $pr->request_number }}</td>
                    <td>{{ $pr->vendor->name }}</td>
                    <td>{{ $pr->request_date }}</td>
                    <td>{{ ucfirst($pr->status) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <h4 class="mt-4">Purchase Orders (PO)</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>PO Number</th>
                    <th>Vendor</th>
                    <th>PO Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($servicePOs as $po)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $po->po_number }}</td>
                    <td>{{ $po->vendor->name }}</td>
                    <td>{{ $po->po_date }}</td>
                    <td>{{ ucfirst($po->status) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
