<?php

namespace App\Http\Controllers\Purchase;

use App\Models\Supplier;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequest;
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
        $suppliers = Supplier::all();
        $purchaseRequests = PurchaseRequest::with('items.product')->get();

        $selectedRequestId = $request->get('request_id');
        $selectedRequest = $selectedRequestId ? PurchaseRequest::with('items.product')->find($selectedRequestId) : null;

        return view('purchase.orders.create', compact('suppliers', 'purchaseRequests', 'selectedRequest'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0',
        ]);

        try {
            $purchaseOrder = PurchaseOrder::create([
                'order_number' => $this->generateOrderNumber(),
                'supplier_id' => $validated['supplier_id'],
                'purchase_request_id' => $request['request_id'],
                'billed' => 0,
                'status' => 'pending',
            ]);

            foreach ($validated['products'] as $product) {
                $purchaseOrder->items()->create([
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity'],
                    'price' => $product['price'],
                ]);
            }
            return redirect()->route('purchase.orders.index')->with('success', 'Purchase Order created successfully.');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->withErrors('Error creating Purchase Order: ' . $e->getMessage());
        }
    }






    public function show($id)
    {
        $purchaseOrder = PurchaseOrder::with('purchaseRequest.items.product')->findOrFail($id);
        return view('purchase.orders.show', compact('purchaseOrder'));
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
