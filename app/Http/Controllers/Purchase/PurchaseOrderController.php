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
        return view('purchase.orders.index', compact('purchaseOrders'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $products = Product::all();
        $purchaseRequests = PurchaseRequest::all();
        
        return view('purchase.orders.create', compact('suppliers', 'products', 'purchaseRequests'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'purchase_request_id' => 'nullable|exists:purchase_requests,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        // Create the purchase order
        $purchaseOrder = PurchaseOrder::create([
            'supplier_id' => $request->supplier_id,
            'purchase_request_id' => $request->purchase_request_id,
        ]);

        // Add products to the purchase order
        foreach ($request->products as $productData) {
            $purchaseOrder->products()->attach($productData['product_id'], ['quantity' => $productData['quantity']]);
        }

        return redirect()->route('purchase.orders.index')->with('success', 'Purchase Order created successfully.');
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

    public function update(Request $request, $id)
    {
        $request->validate([
            'purchase_request_id' => 'required|exists:purchase_requests,id',
        ]);

        $purchaseOrder = PurchaseOrder::findOrFail($id);
        $purchaseOrder->update([
            'purchase_request_id' => $request->purchase_request_id,
        ]);

        return redirect()->route('purchase.orders.index')->with('success', 'Purchase order updated successfully.');
    }

    public function destroy($id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);
        $purchaseOrder->delete();

        return redirect()->route('purchase.orders.index')->with('success', 'Purchase order deleted successfully.');
    }
}
