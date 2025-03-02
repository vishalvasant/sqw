<?php

namespace App\Http\Controllers;

use App\Models\ProductService;
use Illuminate\Http\Request;

class ProductServiceController extends Controller {
    public function index() {
        $services = ProductService::all();
        return view('product_services.index', compact('services'));
    }

    public function create() {
        return view('product_services.create');
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cost' => 'required|numeric',
            'type' => 'required|in:asset_allocation,expense',
        ]);

        ProductService::create($request->all());

        return redirect()->route('product_services.index')->with('success', 'Service added successfully.');
    }

    public function edit(ProductService $productService) {
        return view('product_services.edit', compact('productService'));
    }

    public function update(Request $request, ProductService $productService) {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cost' => 'required|numeric',
            'type' => 'required|in:asset_allocation,expense',
        ]);

        $productService->update($request->all());

        return redirect()->route('product_services.index')->with('success', 'Service updated successfully.');
    }

    public function destroy(ProductService $productService) {
        $productService->delete();
        return redirect()->route('product_services.index')->with('success', 'Service deleted successfully.');
    }
}
