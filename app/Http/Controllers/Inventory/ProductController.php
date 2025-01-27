<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'unit'])->get(); // Load products with their related category and unit
        return view('inventory.products.index', compact('products'));
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
}

