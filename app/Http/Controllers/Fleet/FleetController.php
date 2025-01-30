<?php

namespace App\Http\Controllers\Fleet;

use App\Models\Asset;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\FuelUsage;
use App\Models\FleetCost;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class FleetController extends Controller
{
    // Vehicles
    public function index()
    {
        $vehicles = Vehicle::with('fuelUsages', 'fleetCost', 'driver')->get();
        return view('fleet.index', compact('vehicles'));
    }

    // Add Vehicle
    public function create()
    {
        return view('fleet.create');
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'vehicle_number' => 'required|unique:vehicles',
            'vehicle_type' => 'required',
            'fixed_cost_per_hour' => 'required|numeric',
        ]);

        $asset = Asset::create(
            [
                'asset_name' => $request->vehicle_number,
                'value' => $request->value ?? 0,
                'purchase_date' => $request->purchase_date ?? now(),
                'description' => "Default Vehicle",
                'status' => $request->status ?? 'active',
            ]
        );

        $vehicle = Vehicle::create($request->all());
        return redirect()->route('fleet.vehicles.index')->with('success', 'Vehicle created successfully');
    }

    public function edit(Vehicle $vehicle)
    {
        return view('fleet.edit', compact('vehicle'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'vehicle_number' => 'required',
            'vehicle_type' => 'required',
            'fixed_cost_per_hour' => 'required|numeric',
        ]);

        $vehicle->update($request->all());
        return redirect()->route('fleet.vehicles.index')->with('success', 'Vehicle updated successfully');
    }

    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();
        return redirect()->route('fleet.vehicles.index')->with('success', 'Vehicle deleted successfully');
    }

    // Drivers
    public function showDrivers()
    {
        $drivers = Driver::with('vehicle')->get();
        return view('fleet.drivers.index', compact('drivers'));
    }

    public function createDriver()
    {
        $vehicles = Vehicle::all();
        return view('fleet.drivers.create', compact('vehicles'));
    }

    public function storeDriver(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'license_number' => 'required|unique:drivers',
            'contact_number' => 'required',
            'vehicle_id' => 'nullable|exists:vehicles,id',
        ]);

        Driver::create($request->all());
        return redirect()->route('fleet.drivers')->with('success', 'Driver added successfully');
    }

    public function assignDriverToVehicle(Request $request, $vehicleId)
    {
        $vehicle = Vehicle::findOrFail($vehicleId);
        $vehicle->driver_id = $request->driver_id;
        $vehicle->save();

        return redirect()->route('fleet.vehicles.index')->with('success', 'Driver assigned successfully');
    }

    // Reports
    public function generateReport()
    {
        // Example of generating fuel usage report
        $fuelUsageReport = FuelUsage::all();
        return view('fleet.reports.index', compact('fuelUsageReport'));
    }

    public function indexMachines()
    {
        $machines = Machine::all(); // Fetch all machines
        return view('fleet.machines.index', compact('machines'));
    }

    public function createMachine()
    {
        return view('fleet.machines.create');
    }

    public function storeMachine(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'per_hour_cost' => 'required|numeric',
        ]);

        Machine::create($validated);

        return redirect()->route('machines.index')->with('success', 'Machine added successfully!');
    }

    public function editMachine($id)
    {
        $machine = Machine::findOrFail($id);
        return view('fleet.machines.edit', compact('machine'));
    }

    public function updateMachine(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'per_hour_cost' => 'required|numeric',
        ]);

        $machine = Machine::findOrFail($id);
        $machine->update($validated);

        return redirect()->route('machines.index')->with('success', 'Machine updated successfully!');
    }

    public function destroyMachine($id)
    {
        $machine = Machine::findOrFail($id);
        $machine->delete();

        return redirect()->route('machines.index')->with('success', 'Machine deleted successfully!');
    }

    public function utilization($id)
    {
        $vehicle = Vehicle::findOrFail($id);

        $fuelUsages = DB::table('fuel_usages')
            ->where('vehicle_id', $id)
            ->select('fuel_amount', 'cost_per_liter', 'date', DB::raw('fuel_amount * cost_per_liter as total_cost'))
            ->get();

        // Calculate Total Fuel, Total Fuel Cost, and Average Cost Per Liter
        $totalFuel = $fuelUsages->sum('fuel_amount');
        $totalFuelCost = $fuelUsages->sum('total_cost');
        $avgCostPerLiter = $totalFuel > 0 ? $totalFuelCost / $totalFuel : 0;

        return view('fleet.utilization', compact('vehicle', 'fuelUsages', 'totalFuel', 'totalFuelCost', 'avgCostPerLiter'));
    }

    public function generateUtilizationReport(Request $request)
    {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        $fuelUsages = DB::table('fuel_usages')
            ->join('vehicles', 'fuel_usages.vehicle_id', '=', 'vehicles.id')
            ->whereBetween('fuel_usages.date', [$fromDate, $toDate])
            ->select(
                'vehicles.vehicle_number',
                'vehicles.vehicle_type',
                DB::raw('SUM(fuel_usages.fuel_amount) as total_fuel'),
                DB::raw('SUM(fuel_usages.fuel_amount * fuel_usages.cost_per_liter) as total_cost'),
                DB::raw('AVG(fuel_usages.cost_per_liter) as avg_cost_per_liter')
            )
            ->groupBy('vehicles.id', 'vehicles.vehicle_number', 'vehicles.vehicle_type')
            ->get();

        // Calculate summary totals
        $totalFuel = $fuelUsages->sum('total_fuel');
        $totalCost = $fuelUsages->sum('total_cost');
        $avgCostPerLiter = ($totalFuel > 0) ? ($totalCost / $totalFuel) : 0;

        return view('fleet.utilization_report', compact('fuelUsages', 'fromDate', 'toDate', 'totalFuel', 'totalCost', 'avgCostPerLiter'));
    }



}
