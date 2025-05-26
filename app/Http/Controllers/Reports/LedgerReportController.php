<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Vendor;
use App\Models\PurchaseOrderItem;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LedgerReportController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'unit'])->get();
        $vendors = Vendor::all();
        
        return view('reports.ledger.index', compact('products', 'vendors'));
    }

    public function generateReport(Request $request)
    {
        $request->validate([
            'product_id' => 'nullable|exists:products,id',
            'vendor_id' => 'nullable|exists:vendors,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $productId = $request->input('product_id');
        $vendorId = $request->input('vendor_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Get product details
        $product = $productId 
            ? Product::with(['unit'])->find($productId)
            : null;

        // Get vendor details if selected
        $vendor = $vendorId ? Supplier::find($vendorId) : null;

        // Get stock movements (purchases)
        $stockMovements = PurchaseOrderItem::select(
                'purchase_order_items.product_id',
                'purchase_orders.created_at as purchase_date',
                'purchase_order_items.quantity as received_quantity',
                'purchase_order_items.price as received_price',
                'purchase_orders.supplier_id',
                'supplier.name as vendor_name',
                'purchase_orders.id as po_id',
                'purchase_orders.order_number'
            )
            ->join('purchase_orders', 'purchase_order_items.purchase_order_id', '=', 'purchase_orders.id')
            ->join('suppliers as supplier', 'purchase_orders.supplier_id', '=', 'supplier.id')
            ->when($productId, function($query) use ($productId) {
                return $query->where('purchase_order_items.product_id', $productId);
            })
            ->when($vendorId, function($query) use ($vendorId) {
                return $query->where('purchase_orders.supplier_id', $vendorId);
            })
            ->whereBetween('purchase_orders.created_at', [$startDate, $endDate])
            ->orderBy('purchase_orders.created_at', 'asc')
            ->with(['purchaseOrder','purchaseOrder.supplier'])
            ->get();

        // Get asset allocations
        $assetAllocations = DB::table('asset_part')
            ->select(
                'asset_part.product_id',
                'assets.asset_name',
                'asset_part.quantity',
                'asset_part.created_at as allocation_date'
            )
            ->join('assets', 'asset_part.asset_id', '=', 'assets.id')
            ->when($productId, function($query) use ($productId) {
                return $query->where('asset_part.product_id', $productId);
            })
            ->whereBetween('asset_part.created_at', [$startDate, $endDate])
            ->get();

        // Calculate opening and closing stock
        $openingStock = $this->calculateOpeningStock($productId, $startDate);
        $closingStock = $this->calculateClosingStock($productId, $endDate);

        // Calculate total received and allocated quantities
        $totalReceived = $stockMovements->sum('received_quantity');
        $totalAllocated = $assetAllocations->sum('quantity');

        return view('reports.ledger.report', [
            'product' => $product,
            'vendor' => $vendor,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'stockMovements' => $stockMovements,
            'assetAllocations' => $assetAllocations,
            'openingStock' => $openingStock,
            'closingStock' => $closingStock,
            'totalReceived' => $totalReceived,
            'totalAllocated' => $totalAllocated,
        ]);
    }

    private function calculateOpeningStock($productId, $date)
    {
        if (!$productId) return 0;
        
        // Get all purchases before the start date
        $purchasesBefore = DB::table('purchase_order_items')
            ->join('purchase_orders', 'purchase_order_items.purchase_order_id', '=', 'purchase_orders.id')
            ->where('purchase_order_items.product_id', $productId)
            ->where('purchase_orders.status', '!=', 'cancelled')
            ->where('purchase_orders.created_at', '<', $date)
            ->sum('purchase_order_items.quantity');
            
        // Get all allocations before the start date
        $allocationsBefore = DB::table('asset_part')
            ->where('product_id', $productId)
            ->where('created_at', '<', $date)
            ->sum('quantity');
            
        // Opening stock = (Purchases - Allocations) before start date
        return $purchasesBefore - $allocationsBefore;
    }
    
    private function calculateClosingStock($productId, $date)
    {
        if (!$productId) return 0;
        
        // Get all purchases up to the end date
        $purchases = DB::table('purchase_order_items')
            ->join('purchase_orders', 'purchase_order_items.purchase_order_id', '=', 'purchase_orders.id')
            ->where('purchase_order_items.product_id', $productId)
            ->where('purchase_orders.status', '!=', 'cancelled')
            ->where('purchase_orders.created_at', '<=', $date . ' 23:59:59')
            ->sum('purchase_order_items.quantity');
            
        // Get all allocations up to the end date
        $allocations = DB::table('asset_part')
            ->where('product_id', $productId)
            ->where('created_at', '<=', $date . ' 23:59:59')
            ->sum('quantity');
            
        // Closing stock = Total purchases - Total allocations
        return $purchases - $allocations;
    }
}
