<?php

namespace App\Http\Controllers\Fleet;

use App\Models\Driver;
use App\Models\Vehicle;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function index()
    {
        $drivers = Driver::all();
        return view('fleet.drivers.index', compact('drivers'));
    }

    public function create()
    {
        $vehicles = Vehicle::all();
        return view('fleet.drivers.create', compact('vehicles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'license_number' => 'nullable|string|max:255|unique:drivers',
            'contact_number' => 'required|string|max:15',
        ]);

        Driver::create($request->all());

        return redirect()->route('drivers.index')->with('success', 'Driver created successfully!');
    }

    public function edit(Driver $driver)
    {
        $vehicles = Vehicle::all();
        return view('fleet.drivers.edit', compact('driver', 'vehicles'));
    }

    public function update(Request $request, Driver $driver)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'license_number' => 'nullable|string|max:255|unique:drivers,license_number,' . $driver->id,
            'contact_number' => 'required|string|max:15',
        ]);

        $driver->update($request->all());

        return redirect()->route('drivers.index')->with('success', 'Driver updated successfully!');
    }

    public function destroy(Driver $driver)
    {
        $driver->delete();

        return redirect()->route('drivers.index')->with('success', 'Driver deleted successfully!');
    }
}
