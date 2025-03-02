<?php

namespace App\Http\Controllers;

use App\Models\ServicePurchaseRequest;
use App\Models\ServicePurchaseRequestItem;
use App\Models\ProductService;
use App\Models\Vendor;
use Illuminate\Http\Request;

class ServicePurchaseRequestController extends Controller
{
    public function index()
    {
        $requests = ServicePurchaseRequest::with('vendor', 'requester')->latest()->get();
        return view('service_pr.index', compact('requests'));
    }

    public function create()
    {
        $vendors = Vendor::all();
        $services = ProductService::all();
        return view('service_pr.create', compact('vendors', 'services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'vendor_id' => 'nullable|exists:vendors,id',
            'request_date' => 'required|date',
            'services' => 'required|array',
            'services.*.service_id' => 'required|exists:product_services,id',
            'services.*.quantity' => 'required|integer|min:1'
        ]);

        $pr = ServicePurchaseRequest::create([
            'request_number' => 'PR-' . time(),
            'vendor_id' => $request->vendor_id,
            'requested_by' => auth()->id(),
            'request_date' => $request->request_date,
            'status' => 'pending'
        ]);

        foreach ($request->services as $service) {
            ServicePurchaseRequestItem::create([
                'service_purchase_request_id' => $pr->id,
                'service_id' => $service['service_id'],
                'quantity' => $service['quantity'],
                'description' => $service['description'] ?? null
            ]);
        }

        return redirect()->route('service_pr.index')->with('success', 'Service PR created successfully.');
    }

    public function show(ServicePurchaseRequest $servicePurchaseRequest)
    {
        return view('service_pr.show', compact('servicePurchaseRequest'));
    }

    public function edit(ServicePurchaseRequest $servicePurchaseRequest)
    {
        $vendors = Vendor::all();
        $services = ProductService::all();
        return view('service_pr.edit', compact('servicePurchaseRequest', 'vendors', 'services'));
    }

    public function update(Request $request, ServicePurchaseRequest $servicePurchaseRequest)
    {
        $request->validate([
            'vendor_id' => 'nullable|exists:vendors,id',
            'request_date' => 'required|date',
            'status' => 'required|in:pending,approved,rejected'
        ]);

        $servicePurchaseRequest->update($request->only('vendor_id', 'request_date', 'status'));

        return redirect()->route('service_pr.index')->with('success', 'Service PR updated successfully.');
    }

    public function destroy(ServicePurchaseRequest $servicePurchaseRequest)
    {
        $servicePurchaseRequest->delete();
        return redirect()->route('service_pr.index')->with('success', 'Service PR deleted successfully.');
    }
}
