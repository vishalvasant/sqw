<?php

namespace App\Http\Controllers\Fleet;

use App\Models\FuelUsage;
use App\Models\Product;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class FuelLogController extends Controller
{
    public function index()
    {
        $fuelLogs = FuelUsage::with('vehicle')->get();
        $fulePrice = $this->getProductAveragePriceTillToday(38);
        return view('fleet.fuel_usages.index', compact('fuelLogs', 'fulePrice'));
    }

    public function getProductAveragePriceTillToday($productId)
    {
        return \DB::table('purchase_request_items')
            ->where('product_id', $productId)
            ->selectRaw('SUM(price * quantity) / SUM(quantity) as avg_price')
            ->value('avg_price') ?? 0;
    }

    public function create()
    {
        $vehicles = Vehicle::all();
        return view('fleet.fuel_usages.create', compact('vehicles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'fuel_amount' => 'required|numeric',
            'cost_per_liter' => 'required|numeric',
            'date' => 'required|date',
        ]);

        $validated['total_cost'] = $validated['fuel_amount'] * $validated['cost_per_liter'];

        FuelUsage::create($validated);

        // Update inventory stock for fuel
        $fuelProduct = Product::where('type', 'fuel')->first();
        if ($fuelProduct) {
            $fuelProduct->decrement('stock', $validated['fuel_amount']);
        }

        return redirect()->route('fuel_usages.index')->with('success', 'Fuel usage recorded successfully!');
    }

}
