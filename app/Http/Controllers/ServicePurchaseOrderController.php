<?php

namespace App\Http\Controllers;

use App\Models\ServicePurchaseOrder;
use App\Models\ServicePurchaseOrderItem;
use App\Models\ServicePurchaseRequest;
use App\Models\ServicePurchaseRequestItem;
use App\Models\ProductService;
use App\Models\Vendor;
use Illuminate\Http\Request;

class ServicePurchaseOrderController extends Controller
{
    public function index()
    {
        $orders = ServicePurchaseOrder::with('vendor', 'purchaseRequest','items.service')->latest()->get();
        $vendors = Vendor::all();
        $productServices = ProductService::all();
        return view('service_po.index', compact('orders','vendors', 'productServices'));
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

        $createdAt = $request->created_at ?? now();

        $po = ServicePurchaseOrder::create([
            'order_number' => $this->generateServiceOrderNumber($createdAt),
            'service_purchase_request_id' => $pr->id,
            'vendor_id' => $pr->vendor_id,
            'order_date' => $request->order_date,
            'bill_no' => $request->bill_no,
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

    private function generateServiceOrderNumber($date)
    {
        $year = \Carbon\Carbon::parse($date)->format('Y');
        $month = \Carbon\Carbon::parse($date)->format('m');

        // Get the last order number for current month/year
        $prefix = "SU-{$year}{$month}";

        // Count the number of PRs for the current month
        $count = ServicePurchaseOrder::where('order_number', 'like', $prefix . '%')
            ->selectRaw("MAX(CAST(SUBSTRING(order_number, -3) AS UNSIGNED)) as max_number")
            ->value('max_number'); // Increment count to start from 001

        // Format count as three-digit number (e.g., 001, 002, 010, 100)
        $nextNumber = $count ? $count + 1 : 1;
        $prNumber = sprintf('%03d', $nextNumber);

        return "SU-{$year}{$month}{$prNumber}";
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
        $servicePurchaseRequest = ServicePurchaseRequest::findOrFail($servicePurchaseOrder->service_purchase_request_id);
        foreach ($servicePurchaseOrder->items as $item) {
            $servicePurchaseRequestItem = ServicePurchaseRequestItem::where('service_purchase_request_id', $servicePurchaseOrder->service_purchase_request_id)->first();
            $servicePurchaseRequestItem['price'] += $item->total_price;
            $servicePurchaseRequestItem->update();
        }
        $servicePurchaseOrder->delete();
        return redirect()->route('service_po.index')->with('success', 'Service PO deleted successfully.');
    }

    public function purchaseServiceOrdersReport(Request $request)
    {
        $query = ServicePurchaseOrder::with('service_purchase_order_items.service') // eager load relations
            ->join('service_purchase_order_items as items', 'items.service_purchase_order_id', '=', 'service_purchase_orders.id')
            ->join('product_services as services', 'services.id', '=', 'items.service_id')
            ->select('service_purchase_orders.*')
            ->distinct(); // avoid duplicates due to joins

        // Filter by date range
        if ($request->filled('from_date')) {
            $fromDate = $request->from_date . ' 00:00:00';
            $query->where('service_purchase_orders.created_at', '>=', $fromDate);
        }

        if ($request->filled('to_date')) {
            $toDate = $request->to_date . ' 23:59:59';
            $query->where('service_purchase_orders.created_at', '<=', $toDate);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('service_purchase_orders.status', $request->status);
        }

        // Filter by billed/unbilled
        if ($request->filled('billed')) {
            $query->where('service_purchase_orders.billed', $request->billed);
        }

        // Filter by vendor(s)
        if ($request->filled('vendor_id')) {
            $query->whereIn('service_purchase_orders.vendor_id', $request->vendor_id);
        }

        // Filter by service(s)
        if ($request->filled('productServices_id')) {
            $query->whereIn('services.id', $request->productServices_id);
        }

        $purchaseOrders = $query->orderByDesc('service_purchase_orders.created_at')->get();

        return view('service_po.reports', compact('purchaseOrders'));
    }

}
