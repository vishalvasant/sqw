<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::all();
        return view('inventory.units.index', compact('units'));
    }

    public function create()
    {
        return view('inventory.units.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'unit_name' => 'required|string|max:255',
        ]);

        Unit::create($validated);
        return redirect()->route('inventory.units.index')->with('success', 'Unit created successfully');
    }

    public function show(Unit $unit)
    {
        return view('inventory.units.show', compact('unit'));
    }

    public function edit(Unit $unit)
    {
        return view('inventory.units.edit', compact('unit'));
    }

    public function update(Request $request, Unit $unit)
    {
        $validated = $request->validate([
            'unit_name' => 'required|string|max:255',
        ]);

        $unit->update($validated);
        return redirect()->route('inventory.units.index')->with('success', 'Unit updated successfully');
    }

    public function destroy(Unit $unit)
    {
        $unit->delete();
        return redirect()->route('inventory.units.index')->with('success', 'Unit deleted successfully');
    }
}

