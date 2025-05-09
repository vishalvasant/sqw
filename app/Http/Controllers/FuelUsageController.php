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
        $fulePrice = $this->getProductAveragePriceTillToday(38); // Assuming 1 is the product ID for fuel
        return view('fuel_usage.index', compact('fuelUsages', 'fulePrice'));
    }

    public function getProductAveragePriceTillToday($productId)
    {
        return \DB::table('purchase_request_items')
            ->where('product_id', $productId)
            ->selectRaw('SUM(price * quantity) / SUM(quantity) as avg_price')
            ->value('avg_price') ?? 0;
    }


    public function getProductAveragePriceByDateRange($productId, $fromDate, $toDate)
    {
        return \DB::table('purchase_request_items')
            ->join('purchase_requests', 'purchase_request_items.purchase_request_id', '=', 'purchase_requests.id')
            ->where('purchase_request_items.product_id', $productId)
            ->whereBetween('purchase_requests.created_at', [$fromDate, $toDate])
            ->selectRaw('SUM(purchase_request_items.price * purchase_request_items.quantity) / SUM(purchase_request_items.quantity) as avg_price')
            ->value('avg_price') ?? 0;
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
            'description' => 'nullable',
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
            'description' => $request->description,
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
            'description' => 'nullable',
        ]);

        $fuelUsage->update([
            'vehicle_id' => $request->vehicle_id,
            'fuel_amount' => $request->fuel_amount,
            'cost_per_liter' => $request->cost_per_liter,
            'total_cost' => $request->fuel_amount * $request->cost_per_liter,
            'date' => $request->date,
            'description' => $request->description,
        ]);

        return redirect()->route('fleet.fuel_usages.index')->with('success', 'Fuel usage record updated successfully.');
    }

    public function destroy(FuelUsage $fuelUsage)
    {
        $product = Product::findOrFail($fuelUsage->product_id);
        $product->stock += $fuelUsage->fuel_amount;
        $product->save();
        $fuelUsage->delete();

        return redirect()->route('fleet.fuel_usages.index')->with('success', 'Fuel usage record deleted successfully.');
    }
}
