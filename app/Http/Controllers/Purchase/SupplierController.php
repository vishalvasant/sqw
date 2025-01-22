<?php

namespace App\Http\Controllers\Purchase;

use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::all();
        return view('purchase.suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('purchase.suppliers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'nullable|email',
            'phone' => 'nullable',
            'address' => 'nullable',
        ]);

        Supplier::create($request->all());
        return redirect()->route('purchase.suppliers.index')->with('success', 'Supplier created successfully.');
    }

    public function show(Supplier $supplier)
    {
        return view('purchase.suppliers.show', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        return view('purchase.suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'nullable|email',
            'phone' => 'nullable',
            'address' => 'nullable',
        ]);

        $supplier->update($request->all());
        return redirect()->route('purchase.suppliers.index')->with('success', 'Supplier updated successfully.');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('purchase.suppliers.index')->with('success', 'Supplier deleted successfully.');
    }
}
