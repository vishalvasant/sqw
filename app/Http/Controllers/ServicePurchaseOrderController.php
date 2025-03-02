<?php

namespace App\Http\Controllers;

use App\Models\ServicePurchaseOrder;
use App\Models\ServicePurchaseOrderItem;
use App\Models\ServicePurchaseRequest;
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

    public function create()
    {
        $purchaseRequests = ServicePurchaseRequest::where('status', 'approved')->get();
        $vendors = Vendor::all();
        return view('service_po.create', compact('purchaseRequests', 'vendors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_pr_id' => 'required|exists:service_purchase_requests,id',
            'order_date' => 'required|date',
        ]);

        $pr = ServicePurchaseRequest::find($request->service_pr_id);

        $po = ServicePurchaseOrder::create([
            'order_number' => 'PO-' . time(),
            'service_purchase_request_id' => $pr->id,
            'vendor_id' => $pr->vendor_id,
            'order_date' => $request->order_date,
            'status' => 'pending'
        ]);

        foreach ($pr->items as $item) {
            ServicePurchaseOrderItem::create([
                'service_purchase_order_id' => $po->id,
                'service_id' => $item->service_id,
                'quantity' => $item->quantity,
                'unit_price' => 0,
                'total_price' => 0
            ]);
        }

        return redirect()->route('service_po.index')->with('success', 'Service PO created successfully.');
    }

    public function show(ServicePurchaseOrder $servicePurchaseOrder)
    {
        return view('service_po.show', compact('servicePurchaseOrder'));
    }

    public function edit(ServicePurchaseOrder $servicePurchaseOrder)
    {
        return view('service_po.edit', compact('servicePurchaseOrder'));
    }

    public function update(Request $request, ServicePurchaseOrder $servicePurchaseOrder)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected,completed'
        ]);

        $servicePurchaseOrder->update($request->only('status'));

        return redirect()->route('service_po.index')->with('success', 'Service PO updated successfully.');
    }

    public function destroy(ServicePurchaseOrder $servicePurchaseOrder)
    {
        $servicePurchaseOrder->delete();
        return redirect()->route('service_po.index')->with('success', 'Service PO deleted successfully.');
    }
}
