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

    public function show($id)
    {
        $servicePurchaseRequest = ServicePurchaseRequest::with('items','services')->findOrFail($id);
        return view('service_pr.show', compact('servicePurchaseRequest'));
    }

    public function edit($id)
    {
        $vendors = Vendor::all();
        $services = ProductService::all();
        $servicePurchaseRequest = ServicePurchaseRequest::with('items','services')->findOrFail($id);
        return view('service_pr.edit', compact('servicePurchaseRequest', 'vendors', 'services'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'vendor_id' => 'nullable|exists:vendors,id',
            'request_date' => 'required|date',
            'services' => 'required|array',
            'services.*.service_id' => 'required|exists:product_services,id',
            'services.*.quantity' => 'required|integer|min:1'
        ]);
        $servicePurchaseRequest = ServicePurchaseRequest::findOrFail($id);
        $servicePurchaseRequest->update([
            'vendor_id' => $request->vendor_id,
            'requested_by' => auth()->id(),
            'request_date' => $request->request_date,
            'status' => $request->status
        ]);

        $existingItemIds = collect($validatedData['services'])->pluck('id')->filter();
        $servicePurchaseRequest->items()->whereNotIn('id', $existingItemIds)->delete();

        foreach ($request->services as $service) {
            if (isset($service['id'])) {
                // Update existing item
                $servicePurchaseRequestItem = ServicePurchaseRequestItem::findOrFail($service['id']);
                $servicePurchaseRequestItem->update([
                    'service_id' => $service['service_id'],
                    'quantity' => $service['quantity'],
                    'description' => $service['description'] ?? null
                ]);
            } else {
                // Add new item
                $servicePurchaseRequest->items()->create([
                    'service_id' => $service['service_id'],
                    'quantity' => $service['quantity'],
                    'description' => $service['description'] ?? null
                ]);
            }
        }

        return redirect()->route('service_pr.index')->with('success', 'Service PR updated successfully.');
    }

    public function destroy(ServicePurchaseRequest $servicePurchaseRequest)
    {
        $servicePurchaseRequest->delete();
        return redirect()->route('service_pr.index')->with('success', 'Service PR deleted successfully.');
    }
}
