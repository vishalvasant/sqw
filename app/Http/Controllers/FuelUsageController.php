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
        $fuelUsages = FuelUsage::with('vehicle','product')->orderBy('date', 'desc')->get();
        return view('fuel_usage.index', compact('fuelUsages'));
    }

    public function create(Request $request)
    {
        $selectedRequestId = $request->get('request_id');
        $selectedRequest = $selectedRequestId ? Vehicle::findOrFail($selectedRequestId) : null;
        $products = Product::all(); // Fetch all products
        $vehicles = Vehicle::all(); // Fetch all vehicles
        return view('fuel_usage.create', compact('vehicles','selectedRequest','products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'fuel_amount' => 'required|numeric|min:0',
            'cost_per_liter' => 'required|numeric|min:0',
            'date' => 'required|date',
        ]);

        $product = Product::findOrFail($request->product_id);
        if ($product->stock >= $request->fuel_amount) {
            // Allocate the product to the asset and deduct stock
            // $asset->products()->attach($product->id, ['quantity' => $request->quantity]);
            $product->stock -= $request->fuel_amount;
            $product->save();
        } else {
            return redirect()->back()->withErrors(['error' => 'Not enough stock for ' . $product->name]);
        }

        $fuelUsage = new FuelUsage([
            'vehicle_id' => $request->vehicle_id,
            'product_id' => $request->product_id,
            'fuel_amount' => $request->fuel_amount,
            'purpose' => $request->vehicle_type,
            'distance_covered' => $request->kmsrun ?? $request->trip ?? 0,
            'cost_per_liter' => $request->cost_per_liter,
            'total_cost' => $request->fuel_amount * $request->cost_per_liter,
            'hours_used' => $request->hours_used ?? 0,
            'date' => $request->date,
        ]);

        $vehicle = Vehicle::findOrFail($request->vehicle_id);
        $vehicle->update([
            'vehicle_id' => $request->vehicle_id,
            'odometer' => $request->readings,
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
