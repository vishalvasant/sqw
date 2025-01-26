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

    public function create(Request $request)
    {
        
        $purchaseRequests = PurchaseRequest::with(['items' => function ($query) {
            $query->where('quantity', '>', 0); // Exclude items with 0 quantity
        }])->whereHas('items', function ($query) {
            $query->where('quantity', '>', 0);
        })->get();

        $selectedRequestId = $request->get('request_id');
        $selectedRequest = $selectedRequestId ? PurchaseRequest::with('items.product')->find($selectedRequestId) : null;
        if($selectedRequest!=null){
            $suppliers = Supplier::findOrFail($selectedRequest->supplier_id);
        }else{
            $suppliers = Supplier::all();
        }
        return view('purchase.orders.create', compact('suppliers', 'purchaseRequests', 'selectedRequest'));
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
        return 'PO-' . strtoupper(uniqid());
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


}
