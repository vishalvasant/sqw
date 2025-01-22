<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Unit;
use Illuminate\Http\Request;

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
}

