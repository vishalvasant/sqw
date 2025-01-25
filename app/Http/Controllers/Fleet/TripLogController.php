<?php

namespace App\Http\Controllers\Fleet;

use App\Models\TripLog;
use App\Models\Fleet;
use App\Models\Driver;
use Illuminate\Http\Request;

class TripLogController extends Controller
{
    public function index()
    {
        $tripLogs = TripLog::all();
        return view('trip_logs.index', compact('tripLogs'));
    }

    public function create()
    {
        $fleets = Fleet::all();
        $drivers = Driver::all();
        return view('trip_logs.create', compact('fleets', 'drivers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fleet_id' => 'required|exists:fleets,id',
            'driver_id' => 'nullable|exists:drivers,id',
            'cost_type' => 'required|in:per_hour,per_km,fixed_trip',
            'duration_or_distance' => 'required|numeric',
            'trip_date' => 'required|date',
        ]);

        $fleet = Fleet::find($request->fleet_id);
        $tripCost = $fleet->calculateCost($request->duration_or_distance);

        TripLog::create([
            'fleet_id' => $request->fleet_id,
            'driver_id' => $request->driver_id,
            'cost_type' => $request->cost_type,
            'duration_or_distance' => $request->duration_or_distance,
            'trip_cost' => $tripCost,
            'trip_date' => $request->trip_date,
        ]);

        return redirect()->route('trip_logs.index')->with('success', 'Trip log created successfully!');
    }
}
