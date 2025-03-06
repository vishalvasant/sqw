<?php

namespace App\Http\Controllers;

use App\Models\ServicePurchaseOrder;
use App\Models\ServicePurchaseOrderItem;
use App\Models\ServicePurchaseRequest;
use App\Models\ServicePurchaseRequestItem;
use App\Models\Service;
use App\Models\Vendor;
use Illuminate\Http\Request;

class ServicePurchaseOrderController extends Controller
{
    public function index()
    {
        $orders = ServicePurchaseOrder::with('vendor', 'purchaseRequest')->latest()->get();
        
        return view('service_po.index', compact('orders'));
    }

    protected function generateSNumber()
    {
        $year = date('Y'); // Get current year (e.g., 2025)
        $month = date('m'); // Get current month (e.g., 02)
    
        // Count the number of PRs for the current month
        $count = ServicePurchaseOrder::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->count() + 1; // Increment count to start from 001
    
        // Format count as three-digit number (e.g., 001, 002, 010, 100)
        $prNumber = sprintf('%03d', $count);
    
        return "SR-{$year}{$month}{$prNumber}";
    }

    public function create(Request $request)
    {
        $selectedRequestId = $request->get('request_id');
        $selectedRequest = $selectedRequestId ? ServicePurchaseRequest::with('items.service')->find($selectedRequestId) : null;
        $purchaseRequests = ServicePurchaseRequest::with(['items'])->get();
        $purchaseRequests = $purchaseRequests->filter(function ($purchaseRequest) {
            return $purchaseRequest->items->sum('price') > 0;
        });
        if($selectedRequest != null) {
            $vendors = Vendor::findOrFail($selectedRequest->vendor_id);
        } else {
            $vendors = Vendor::all();
        }
        $gr_number = $this->generateSNumber();

        return view('service_po.create', compact('purchaseRequests', 'vendors', 'selectedRequest', 'gr_number'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_pr_id' => 'required|exists:service_purchase_requests,id',
            'order_date' => 'required|date',
        ]);
        
        $pr = ServicePurchaseRequest::find($request->service_pr_id);

        $po = ServicePurchaseOrder::create([
            'order_number' => $this->generateServiceOrderNumber(),
            'service_purchase_request_id' => $pr->id,
            'vendor_id' => $pr->vendor_id,
            'order_date' => $request->order_date,
            'status' => 'pending'
        ]);

        foreach ($request->products as $item) {
            $servicePurchaseRequestItem = ServicePurchaseRequestItem::where('service_purchase_request_id', $request->service_pr_id)->first();
            $servicePurchaseRequestItem['price'] -= $item['price'];
            $servicePurchaseRequestItem->update();
            ServicePurchaseOrderItem::create([
                'service_purchase_order_id' => $po->id,
                'service_id' => $item['service_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['price'],
                'total_price' => $item['quantity'] * $item['price']
            ]);
        }

        return redirect()->route('service_po.index')->with('success', 'Service PO created successfully.');
    }

    private function generateServiceOrderNumber()
    {
        $year = date('Y'); // Get current year (e.g., 2025)
        $month = date('m'); // Get current month (e.g., 02)
    
        // Count the number of PRs for the current month
        $count = ServicePurchaseOrder::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->count() + 1; // Increment count to start from 001
    
        // Format count as three-digit number (e.g., 001, 002, 010, 100)
        $prNumber = sprintf('%03d', $count);
    
        return "SO-{$year}{$month}{$prNumber}";
    }

    public function show($id)
    {
        $servicePurchaseOrder = ServicePurchaseOrder::with('vendor', 'purchaseRequest', 'items.service')->findOrFail($id);
        return view('service_po.show', compact('servicePurchaseOrder'));
    }

    public function edit($id)
    {
        $servicePurchaseOrder = ServicePurchaseOrder::findOrFail($id);
        $approvedRequests = ServicePurchaseRequest::where('status', 'approved')->get();
        return view('service_po.edit', compact('servicePurchaseOrder', 'approvedRequests'));
    }
    
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,cancelled',
        ]);

        $purchaseOrder = ServicePurchaseOrder::findOrFail($id);
        $purchaseOrder->status = $request->status;
        $purchaseOrder->save();

        return redirect()->route('service_po.index')->with('success', 'Status updated successfully.');
    }

    public function updateBilled(Request $request, $id)
    {
        $request->validate([
            'billed' => 'required|boolean',
        ]);

        $purchaseOrder = ServicePurchaseOrder::findOrFail($id);
        $purchaseOrder->billed = $request->billed;
        $purchaseOrder->save();

        return redirect()->route('service_po.index')->with('success', 'Billed status updated successfully.');
    }

    public function update(Request $request, ServicePurchaseOrder $servicePurchaseOrder)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected,completed'
        ]);

        $servicePurchaseOrder->update($request->only('status'));

        return redirect()->route('service_po.index')->with('success', 'Service PO updated successfully.');
    }

    public function destroy($id)
    {
        $servicePurchaseOrder = ServicePurchaseOrder::findOrFail($id);
        $servicePurchaseOrder->delete();
        return redirect()->route('service_po.index')->with('success', 'Service PO deleted successfully.');
    }

    public function purchaseServiceOrdersReport(Request $request)
    {
        $query = ServicePurchaseOrder::with('vendor');

        // Filter by date range
        // if ($request->has('from_date') && $request->has('to_date')) {
        //     $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
        // }
        if ($request->has('from_date')) {
            $fromDate = $request->from_date . ' 00:00:00';
            $query->where('created_at', '>=', $fromDate);
        }
        if ($request->has('to_date')) {
            $toDate = $request->to_date . ' 23:59:59';
            $query->where('created_at', '<=', $toDate);
        }

        // Filter by status
        if ($request->has('status')) {
            if($request->status != ""){
                $query->where('status', $request->status);
            }
        }

        // // Filter by billed/unbilled
        if ($request->has('billed')) {
            if($request->billed != ""){
                $query->where('billed', $request->billed);
            }
        }

        $purchaseOrders = $query->get();

        return view('service_po.reports', compact('purchaseOrders'));
    }
}
