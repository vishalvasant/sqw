<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Unit;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'unit'])->get(); // Load products with their related category and unit
        $suppliers = Supplier::all(); // Fetch all suppliers
        return view('inventory.products.index', compact('products','suppliers'));
    }

    public function create()
    {
        $categories = Category::all(); // Fetch all categories
        $units = Unit::all(); // Fetch all units
        return view('inventory.products.create', compact('categories', 'units'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'unit_id' => 'required|exists:units,id',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'min_qty' => 'required|numeric',
        ]);

        Product::create($validated);
        return redirect()->route('inventory.products.index')->with('success', 'Product created successfully');
    }

    public function show(Product $product)
    {
        return view('inventory.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $units = Unit::all();
        return view('inventory.products.edit', compact('product', 'categories', 'units'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'unit_id' => 'required|exists:units,id',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'min_qty' => 'required|numeric',
        ]);
        
        $product->update($validated);
        return redirect()->route('inventory.products.index')->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('inventory.products.index')->with('success', 'Product deleted successfully');
    }

    public function productUtilization($id)
    {
        $product = Product::findOrFail($id);

        // Fetch utilization data
        $utilizationData = DB::table('purchase_order_items')
            ->join('purchase_orders', 'purchase_order_items.purchase_order_id', '=', 'purchase_orders.id')
            ->join('suppliers', 'purchase_orders.supplier_id', '=', 'suppliers.id')
            ->where('purchase_order_items.product_id', $id)
            ->select(
                'purchase_order_items.quantity',
                'purchase_order_items.price',
                'purchase_orders.order_number',
                'purchase_orders.gr_number',
                'suppliers.name',
                'purchase_orders.created_at as received_date'
            )
            ->get();
        // Calculate total quantity and average price
        $totalQuantity = $utilizationData->sum('quantity');
        $avgPrice = $utilizationData->avg('price');
        $currentStock = $product->stock;
        // Pass data to the view
        return view('inventory.products.utilization_detail', compact('product', 'utilizationData', 'totalQuantity', 'avgPrice', 'currentStock'));
    }

    public function generateReport(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        // Fetch product utilization data within the selected date range

        $suppliers = $request->suppliers_id;

        $products = Product::with(['category', 'unit'])
            ->leftJoin('purchase_order_items', 'products.id', '=', 'purchase_order_items.product_id')
            ->leftJoin('purchase_orders', 'purchase_order_items.purchase_order_id', '=', 'purchase_orders.id')
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
            return $query->whereBetween('purchase_orders.created_at', [$startDate, $endDate]);
            })
            ->when(!empty($suppliers), function ($query) use ($suppliers) {
            return $query->whereIn('purchase_orders.supplier_id', $suppliers);
            })
            ->select(
            'products.id',
            'products.name',
            'products.stock',
            DB::raw('SUM(purchase_order_items.quantity) as total_received'),
            DB::raw('AVG(purchase_order_items.price) as avg_price'),
            DB::raw('MAX(purchase_orders.created_at) as last_received_date')
            )
            ->groupBy('products.id', 'products.name', 'products.stock')
            ->get();
        
        // $products = Product::with(['category', 'unit'])
        //     ->leftJoin('purchase_order_items', 'products.id', '=', 'purchase_order_items.product_id')
        //     ->leftJoin('purchase_orders', 'purchase_order_items.purchase_order_id', '=', 'purchase_orders.id')
        //     ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
        //         return $query->whereBetween('purchase_orders.created_at', [$startDate, $endDate]);
        //     })
        //     ->select(
        //         'products.id',
        //         'products.name',
        //         'products.stock',
        //         DB::raw('SUM(purchase_order_items.quantity) as total_received'),
        //         DB::raw('AVG(purchase_order_items.price) as avg_price'),
        //         DB::raw('MAX(purchase_orders.created_at) as last_received_date')
        //     )
        //     ->groupBy('products.id', 'products.name', 'products.stock')
        //     ->get();

        return view('inventory.products.report', compact('products', 'startDate', 'endDate'));
    }
}

