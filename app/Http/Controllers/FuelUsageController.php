<?php

namespace App\Http\Controllers;

use App\Models\FuelUsage;
use App\Models\Product;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class FuelUsageController extends Controller
{
    public function index()
    {
        $fuelUsages = FuelUsage::with('vehicle')->orderBy('date', 'desc')->get();
        return view('fuel_usage.index', compact('fuelUsages'));
    }

    public function create()
    {
        $vehicles = Vehicle::all(); // Fetch all vehicles
        return view('fuel_usage.create', compact('vehicles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'fuel_amount' => 'required|numeric|min:0',
            'cost_per_liter' => 'required|numeric|min:0',
            'date' => 'required|date',
        ]);

        $fuelUsage = new FuelUsage([
            'vehicle_id' => $request->vehicle_id,
            'fuel_amount' => $request->fuel_amount,
            'cost_per_liter' => $request->cost_per_liter,
            'total_cost' => $request->fuel_amount * $request->cost_per_liter,
            'date' => $request->date,
        ]);

        $fuelUsage->save();

        return redirect()->route('fleet.fuel_usages.index')->with('success', 'Fuel usage record added successfully.');
    }

    public function edit(FuelUsage $fuelUsage)
    {
        $vehicles = Vehicle::all();
        $product = Product::all();
        return view('fuel_usage.edit', compact('fuelUsage', 'vehicles','product'));
    }

    public function update(Request $request, FuelUsage $fuelUsage)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'fuel_amount' => 'required|numeric|min:0',
            'cost_per_liter' => 'required|numeric|min:0',
            'date' => 'required|date',
        ]);

        $fuelUsage->update([
            'vehicle_id' => $request->vehicle_id,
            'fuel_amount' => $request->fuel_amount,
            'cost_per_liter' => $request->cost_per_liter,
            'total_cost' => $request->fuel_amount * $request->cost_per_liter,
            'date' => $request->date,
        ]);

        return redirect()->route('fleet.fuel_usages.index')->with('success', 'Fuel usage record updated successfully.');
    }

    public function destroy(FuelUsage $fuelUsage)
    {
        $fuelUsage->delete();

        return redirect()->route('fleet.fuel_usages.index')->with('success', 'Fuel usage record deleted successfully.');
    }
}
