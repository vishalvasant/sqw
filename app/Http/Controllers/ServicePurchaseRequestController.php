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
            'services.*.price' => 'required|integer|min:1'
        ]);

        $pr = ServicePurchaseRequest::create([
            'request_number' => $this->generateServiceRequestNumber(),
            'vendor_id' => $request->vendor_id,
            'requested_by' => auth()->id(),
            'request_date' => $request->request_date,
            'description' => $request->description,
            'status' => 'pending'
        ]);

        foreach ($request->services as $service) {
            ServicePurchaseRequestItem::create([
                'service_purchase_request_id' => $pr->id,
                'service_id' => $service['service_id'],
                'quantity' => $service['quantity'],
                'price' => $service['price'],
            ]);
        }

        return redirect()->route('service_pr.index')->with('success', 'Service PR created successfully.');
    }

    protected function generateServiceRequestNumber()
    {
        $year = date('Y'); // Get current year (e.g., 2025)
        $month = date('m'); // Get current month (e.g., 02)
    
        // Count the number of PRs for the current month
        $count = ServicePurchaseRequest::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->count() + 1; // Increment count to start from 001
    
        // Format count as three-digit number (e.g., 001, 002, 010, 100)
        $prNumber = sprintf('%03d', $count);
    
        return "SR-{$year}{$month}{$prNumber}";
    }

    public function show($id)
    {
        $servicePurchaseRequest = ServicePurchaseRequest::with('items','services','vendor')->findOrFail($id);
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
        $validatedData = $request->validate([
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
            'description' => $request->description,
            'status' => $request->status
        ]);
        
        $existingItemIds = collect($validatedData['services'])->pluck('id')->filter();
        $servicePurchaseRequest->items()->whereNotIn('id', $existingItemIds)->delete();

        foreach ($request->services as $service) {
            if (isset($service['id'])) {
                // Update existing item
                $servicePurchaseRequestItem = ServicePurchaseRequestItem::findOrFail($service['service_id']);
                $servicePurchaseRequestItem->update([
                    'service_id' => $service['service_id'],
                    'quantity' => $service['quantity'],
                    'price' => $service['price'] ?? null
                ]);
            } else {
                // Add new item
                $servicePurchaseRequest->items()->create([
                    'service_purchase_request_id' => $servicePurchaseRequest->id,
                    'service_id' => $service['service_id'],
                    'quantity' => $service['quantity'],
                    'price' => $service['price'] ?? null
                ]);
            }
        }

        return redirect()->route('service_pr.index')->with('success', 'Service PR updated successfully.');
    }

    public function destroy($id)
    {
        $servicePurchaseRequest = ServicePurchaseRequest::findOrFail($id);
        $servicePurchaseRequest->delete();
        return redirect()->route('service_pr.index')->with('success', 'Service PR deleted successfully.');
    }

    public function servicePurchaseRequestsReport(Request $request)
    {
        $query = ServicePurchaseRequest::with(['requester', 'vendor', 'items.service']);
        if ($request->has('from_date')) {
            $fromDate = $request->from_date . ' 00:00:00';
            $query->where('created_at', '>=', $fromDate);
        }
        if ($request->has('to_date')) {
            $toDate = $request->to_date . ' 23:59:59';
            $query->where('created_at', '<=', $toDate);
        }

        // if ($request->has('from_date') && $request->has('to_date')) {
        //     $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
        // }
        if ($request->has('status')) {
            if($request->status != "all"){
                $query->where('status', $request->status);
            }
        }
        $purchaseRequests = $query->get();
        return view('service_pr.reports', compact('purchaseRequests'));
    }
}
