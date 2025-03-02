@extends('layouts.admin')

@section('page-title', 'Create Service Purchase Order')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Create Service PO</h3>
    </div>
    <form action="{{ route('service_po.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label for="service_pr_id">Select Purchase Request</label>
                <select name="service_pr_id" class="form-control" required>
                    <option value="">Select PR</option>
                    @foreach($purchaseRequests as $pr)
                        <option value="{{ $pr->id }}">PR# {{ $pr->request_number }} - {{ $pr->vendor->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="order_date">PO Date</label>
                <input type="date" name="order_date" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Services Ordered</label>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Service</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody id="service-table">
                        <!-- Services will be dynamically added here -->
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Submit PO</button>
            <a href="{{ route('service_po.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelector("[name='service_pr_id']").addEventListener("change", function () {
            let prId = this.value;
            if (prId) {
                fetch(`/service_pr/${prId}/services`)
                    .then(response => response.json())
                    .then(data => {
                        let tableBody = document.querySelector("#service-table");
                        tableBody.innerHTML = "";
                        data.forEach((service, index) => {
                            let row = `<tr>
                                <td>${service.name}</td>
                                <td>${service.quantity}</td>
                                <td><input type="number" name="services[${index}][unit_price]" class="form-control" required></td>
                                <td><span class="total-price">0</span></td>
                            </tr>`;
                            tableBody.insertAdjacentHTML("beforeend", row);
                        });
                    });
            }
        });

        document.addEventListener("input", function (event) {
            if (event.target.matches("[name^='services'][name$='[unit_price]']")) {
                let row = event.target.closest("tr");
                let quantity = parseFloat(row.cells[1].innerText);
                let unitPrice = parseFloat(event.target.value);
                row.querySelector(".total-price").innerText = (quantity * unitPrice).toFixed(2);
            }
        });
    });
</script>
@endsection
