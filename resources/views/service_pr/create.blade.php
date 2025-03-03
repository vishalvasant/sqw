@extends('layouts.admin')

@section('page-title', 'Create Service Purchase Request')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Create Service PR</h3>
    </div>
    <form action="{{ route('service_pr.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <label for="vendor_id">Vendor</label>
                    <select name="vendor_id" class="form-control">
                        <option value="">Select Vendor</option>
                        @foreach($vendors as $vendor)
                            <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="request_date">Request Date</label>
                    <input type="date" name="request_date" class="form-control" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label for="request_date">Description</label>
                    <textarea class="form-control" name="description" id="description"></textarea>
                </div>
            </div>
            <div class="form-group">
                <label>Services</label>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Service</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th><button type="button" class="btn btn-sm btn-success add-row"><i class="fa fa-plus"></i></button></th>
                        </tr>
                    </thead>
                    <tbody id="service-table">
                        <tr>
                            <td>
                                <select name="services[0][service_id]" class="form-control">
                                    <option value="">Select Service</option>
                                    @foreach($services as $service)
                                        <option value="{{ $service->id }}">{{ $service->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" name="services[0][quantity]" class="form-control" required></td>
                            <td><input type="text" name="services[0][price]" class="form-control"></td>
                            <td><button type="button" class="btn btn-danger btn-sm remove-row"><i class="fa fa-trash"></i></button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Submit SR</button>
            <a href="{{ route('service_pr.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let rowIndex = 1;
        document.querySelector(".add-row").addEventListener("click", function () {
            let newRow = `<tr>
                            <td>
                                <select name="services[${rowIndex}][service_id]" class="form-control">
                                    <option value="">Select Service</option>
                                    @foreach($services as $service)
                                        <option value="{{ $service->id }}">{{ $service->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" name="services[${rowIndex}][quantity]" class="form-control" required></td>
                            <td><input type="text" name="services[${rowIndex}][price]" class="form-control"></td>
                            <td><button type="button" class="btn btn-danger btn-sm remove-row"><i class="fa fa-trash"></i></button></td>
                        </tr>`;
            document.querySelector("#service-table").insertAdjacentHTML("beforeend", newRow);
            rowIndex++;
        });

        document.addEventListener("click", function (event) {
            if (event.target.classList.contains("remove-row")) {
                event.target.closest("tr").remove();
            }
        });
    });
</script>
@endsection
