<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::all();
        return view('inventory.warehouses.index', compact('warehouses'));
    }

    public function create()
    {
        return view('inventory.warehouses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'warehouse_name' => 'required|unique:warehouses',
            'location' => 'required',
        ]);

        Warehouse::create($request->all());
        return redirect()->route('warehouses.index')->with('success', 'Warehouse created successfully.');
    }

    public function edit(Warehouse $warehouse)
    {
        return view('inventory.warehouses.edit', compact('warehouse'));
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $request->validate([
            'warehouse_name' => 'required|unique:warehouses,warehouse_name,' . $warehouse->id,
            'location' => 'required',
        ]);

        $warehouse->update($request->all());
        return redirect()->route('warehouses.index')->with('success', 'Warehouse updated successfully.');
    }

    public function destroy(Warehouse $warehouse)
    {
        $warehouse->delete();
        return redirect()->route('warehouses.index')->with('success', 'Warehouse deleted successfully.');
    }
}

