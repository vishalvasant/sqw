<?php

namespace App\Http\Controllers\Purchase;

use App\Http\Controllers\Controller;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestItem;
use App\Models\Product;
use App\Models\Unit;
use App\Models\User;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseRequestController extends Controller
{
    public function index()
    {
        $purchaseRequests = PurchaseRequest::with(['items', 'user'])->get();
        return view('purchase.requests.index', compact('purchaseRequests'));
    }

    public function create()
    {
        $products = Product::all();
        $suppliers = Supplier::all();
        return view('purchase.requests.create', compact('products','suppliers'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high',
            'supplier_id' => 'required|exists:suppliers,id',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);
        
        $purchaseRequest = PurchaseRequest::create([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'priority' => $validatedData['priority'],
            'supplier_id' => $validatedData['supplier_id'],
            'created_by' => auth()->user()->id,
            'status' => 'pending',
            'request_number' => $this->generateRequestNumber(),
        ]);

        foreach ($validatedData['items'] as $item) {
            $purchaseRequest->items()->create($item);
        }

        return redirect()->route('purchase.requests.index')->with('success', 'Purchase request created successfully.');
    }

    protected function generateRequestNumber()
    {
        $year = date('Y'); // Get current year (e.g., 2025)
        $month = date('m'); // Get current month (e.g., 02)
    
        // Count the number of PRs for the current month
        $count = PurchaseRequest::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->count() + 1; // Increment count to start from 001
    
        // Format count as three-digit number (e.g., 001, 002, 010, 100)
        $prNumber = sprintf('%03d', $count);
    
        return "PR-{$year}{$month}{$prNumber}";
    }


    public function show($id)
    {
        $purchaseRequest = PurchaseRequest::with('items.product')->findOrFail($id);
        return view('purchase.requests.show', compact('purchaseRequest'));
    }

    public function edit($id)
    {
        $purchaseRequest = PurchaseRequest::with('items')->findOrFail($id);
        $suppliers = Supplier::all();
        $products = Product::all();

        return view('purchase.requests.edit', compact('purchaseRequest', 'suppliers', 'products'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high',
            'supplier_id' => 'required|exists:suppliers,id',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        $purchaseRequest = PurchaseRequest::findOrFail($id);
        $purchaseRequest->update([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'priority' => $validatedData['priority'],
            'status' => $request['status'],
            'supplier_id' => $validatedData['supplier_id'],
        ]);

        $existingItemIds = collect($validatedData['items'])->pluck('id')->filter();
        $purchaseRequest->items()->whereNotIn('id', $existingItemIds)->delete();

        foreach ($validatedData['items'] as $item) {
            if (isset($item['id'])) {
                // Update existing item
                $purchaseRequestItem = PurchaseRequestItem::findOrFail($item['id']);
                $purchaseRequestItem->update([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            } else {
                // Add new item
                $purchaseRequest->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }
        }

        return redirect()->route('purchase.requests.index')->with('success', 'Purchase request updated successfully.');
    }


    public function destroy($id)
    {
        $purchaseRequest = PurchaseRequest::findOrFail($id);
        $purchaseRequest->delete();

        return redirect()->route('purchase.requests.index')->with('success', 'Purchase request deleted successfully.');
    }

    public function getDetails($id)
    {
        $purchaseRequest = PurchaseRequest::with('products', 'supplier')->findOrFail($id);

        return response()->json([
            'supplier_id' => $purchaseRequest->supplier_id,
            'products' => $purchaseRequest->products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                ];
            }),
            'total_amount' => $purchaseRequest->total_amount,
        ]);
    }

    public function purchaseRequestsReport(Request $request)
    {
        $query = PurchaseRequest::with(['user', 'supplier']);
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
        return view('purchase.requests.reports', compact('purchaseRequests'));
    }

}
