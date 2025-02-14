<?php

namespace App\Http\Controllers\Purchase;

use App\Models\Supplier;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequest;
use App\Models\PurchaseOrderItem;
use App\Models\PurchaseRequestItem;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $purchaseOrders = PurchaseOrder::with('purchaseRequest')->get();
        $suppliers = Supplier::all();
        
        return view('purchase.orders.index', compact('purchaseOrders','suppliers'));
    }

    protected function generateGNumber()
    {
        $year = date('Y'); // Get current year (e.g., 2025)
        $month = date('m'); // Get current month (e.g., 02)
    
        // Count the number of PRs for the current month
        $count = PurchaseOrder::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->count() + 1; // Increment count to start from 001
    
        // Format count as three-digit number (e.g., 001, 002, 010, 100)
        $prNumber = sprintf('%03d', $count);
    
        return "GR-{$year}{$month}{$prNumber}";
    }

    public function create(Request $request)
    {
        $selectedRequestId = $request->get('request_id');
        $selectedRequest = $selectedRequestId ? PurchaseRequest::with('items.product')->find($selectedRequestId) : null;
        $purchaseRequests = PurchaseRequest::with(['items' => function ($query) {
            $query->where('quantity', '>', 0); // Exclude items with 0 quantity
        }])->whereHas('items', function ($query) {
            $query->where('quantity', '>', 0);
        })->get();
        
        if($selectedRequest!=null){
            $suppliers = Supplier::findOrFail($selectedRequest->supplier_id);
        }else{
            $suppliers = Supplier::all();
        }
        $gr_number = $this->generateGNumber();
        return view('purchase.orders.create', compact('suppliers', 'purchaseRequests', 'selectedRequest','gr_number'));
    }


    public function store(Request $request)
    {
        try {
            // Create Purchase Order
            $purchaseOrder = new PurchaseOrder();
            $purchaseOrder->order_number = $this->generateOrderNumber();
            $purchaseOrder->supplier_id = $request->supplier_id;
            $purchaseOrder->status = 'pending';
            $purchaseOrder->billed = 0; // Default
            $purchaseOrder->gr_number = $request->gr_number ?? 0;
            $purchaseOrder->purchase_request_id = $request->request_id; // Default
            $purchaseOrder->save();

            foreach ($request->products as $product) {
                $purchaseOrderItem = new PurchaseOrderItem();
                $purchaseOrderItem->purchase_order_id = $purchaseOrder->id;
                $purchaseOrderItem->product_id = $product['product_id'];
                $purchaseOrderItem->quantity = $product['quantity'];
                $purchaseOrderItem->price = $product['price'];
                $purchaseOrderItem->save();

                // Update Product Stock
                $productModel = Product::find($product['product_id']);
                $productModel->stock += $product['quantity'];
                $productModel->save();

                // Update Purchase Request Quantity
                $purchaseRequestItem = PurchaseRequestItem::find($product['purchase_request_item_id']);
                if ($purchaseRequestItem) {
                    $purchaseRequestItem->quantity -= $product['quantity'];
                    $purchaseRequestItem->save();

                    // Check if quantity is 0 and mark as inactive (optional)
                    if ($purchaseRequestItem->quantity <= 0) {
                        $purchaseRequestItem->delete(); // or $purchaseRequestItem->update(['status' => 'inactive']);
                    }
                }
            }
            return redirect()->route('purchase.orders.index')->with('success', 'Purchase Order created successfully.');
        } catch (\Exception $e) {
            dd($e);
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $order = PurchaseOrder::with('items', 'supplier')->findOrFail($id);
        return view('purchase.orders.show', compact('order'));
    }

    public function edit($id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);
        $approvedRequests = PurchaseRequest::where('status', 'approved')->get();
        return view('purchase.orders.edit', compact('purchaseOrder', 'approvedRequests'));
    }

    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        $validatedData = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'status' => 'required|in:pending,completed,cancelled',
            'billed' => 'nullable|boolean',
        ]);

        $validatedData['billed'] = $request->has('billed') ? true : false;

        $purchaseOrder->update($validatedData);

        return redirect()->route('purchase.orders.index')->with('success', 'Purchase Order updated successfully.');
    }


    public function destroy($id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);
        $purchaseOrder->delete();

        return redirect()->route('purchase.orders.index')->with('success', 'Purchase order deleted successfully.');
    }

    public function markAsBilled($id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);

        if ($purchaseOrder->status === 'billed') {
            return redirect()->back()->with('info', 'This purchase order is already billed.');
        }

        $purchaseOrder->update(['status' => 'billed']);
        return redirect()->route('purchase.orders.index')->with('success', 'Purchase Order marked as billed.');
    }

    private function generateOrderNumber()
    {
        $year = date('Y'); // Get current year (e.g., 2025)
        $month = date('m'); // Get current month (e.g., 02)
    
        // Count the number of PRs for the current month
        $count = PurchaseOrder::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->count() + 1; // Increment count to start from 001
    
        // Format count as three-digit number (e.g., 001, 002, 010, 100)
        $prNumber = sprintf('%03d', $count);
    
        return "PO-{$year}{$month}{$prNumber}";
    }

    public function receiveDocket($id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);
        $purchaseOrder->status = 'completed';
        $purchaseOrder->save();

        return redirect()->route('purchase.orders.index')->with('success', 'Docket received and status updated to Completed.');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,cancelled',
        ]);

        $purchaseOrder = PurchaseOrder::findOrFail($id);
        $purchaseOrder->status = $request->status;
        $purchaseOrder->save();

        return redirect()->route('purchase.orders.index')->with('success', 'Status updated successfully.');
    }


    public function updateBilled(Request $request, $id)
    {
        $request->validate([
            'billed' => 'required|boolean',
        ]);

        $purchaseOrder = PurchaseOrder::findOrFail($id);
        $purchaseOrder->billed = $request->billed;
        $purchaseOrder->save();

        return redirect()->route('purchase.orders.index')->with('success', 'Billed status updated successfully.');
    }

    public function purchaseOrdersReport(Request $request)
    {
        $query = PurchaseOrder::with('supplier');

        // Filter by date range
        if ($request->has('from_date') && $request->has('to_date')) {
            $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
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

        return view('purchase.orders.reports', compact('purchaseOrders'));
    }


}
