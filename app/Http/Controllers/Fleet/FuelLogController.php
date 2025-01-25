<?php

namespace App\Http\Controllers\Fleet;

use App\Models\FuelLog;
use App\Models\Product;
use App\Models\Fleet;
use Illuminate\Http\Request;

class FuelLogController extends Controller
{
    public function index()
    {
        $fuelLogs = FuelLog::all();
        return view('fuel_logs.index', compact('fuelLogs'));
    }

    public function create()
    {
        $fleets = Fleet::all();
        $products = Product::all(); // Assuming you have an inventory of products (fuel)
        return view('fuel_logs.create', compact('fleets', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fleet_id' => 'required|exists:fleets,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric',
            'cost' => 'required|numeric',
            'refueled_at' => 'required|date',
        ]);

        FuelLog::create($request->all());

        return redirect()->route('fuel_logs.index')->with('success', 'Fuel log created successfully!');
    }
}
