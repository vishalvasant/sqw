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
            'created_at' => 'nullable|date', // made nullable
            'request_date' => 'required|date',
            'services' => 'required|array',
            'services.*.service_id' => 'required|exists:product_services,id',
            'services.*.price' => 'required|numeric|min:1'
        ]);

        $createdAt = $validatedData['created_at'] ?? now();

        $pr = ServicePurchaseRequest::create([
            'request_number' => $this->generateServiceRequestNumber($createdAt),
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
        $year = \Carbon\Carbon::parse($date)->format('Y');
        $month = \Carbon\Carbon::parse($date)->format('m');
    
        // Count the number of PRs for the current month
        // Match PR numbers like PR-202505001, PR-202505045, etc.
        $prefix = "SR-{$year}{$month}";
    
        // Get the max numeric part from PR numbers with the given prefix
        $lastNumber = ServicePurchaseRequest::where('request_number', 'like', $prefix . '%')
            ->selectRaw("MAX(CAST(SUBSTRING(request_number, -3) AS UNSIGNED)) as max_number")
            ->value('max_number');
    
        $nextNumber = $lastNumber ? $lastNumber + 1 : 1;
    
        $formattedNumber = sprintf('%03d', $nextNumber);
        return "{$prefix}{$formattedNumber}";
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
