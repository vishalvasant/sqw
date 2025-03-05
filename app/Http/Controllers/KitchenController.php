<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KitchenRecord;

class KitchenController extends Controller
{
    public function index(Request $request)
    {
        $query = KitchenRecord::query();

        // Filtering by date range if provided
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        $kitchenRecords = $query->orderBy('date', 'desc')->get();

        return view('kitchen.index', compact('kitchenRecords'));
    }

    public function create()
    {
        return view('kitchen.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'breakfast_count' => 'required|integer|min:0',
            'lunch_count' => 'required|integer|min:0',
            'dinner_count' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        KitchenRecord::create($request->all());

        return redirect()->route('kitchen.index')->with('success', 'Kitchen record added successfully.');
    }

    public function edit(KitchenRecord $kitchen)
    {
        return view('kitchen.edit', compact('kitchen'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'breakfast_count' => 'required|integer|min:0',
            'lunch_count' => 'required|integer|min:0',
            'dinner_count' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);
        $kitchenRecord = KitchenRecord::where('id', $request->id)->first();
        $kitchenRecord->date = $request->date;
        $kitchenRecord->breakfast_count = $request->breakfast_count;
        $kitchenRecord->lunch_count = $request->lunch_count;
        $kitchenRecord->dinner_count = $request->dinner_count;
        $kitchenRecord->description = $request->description;
        $kitchenRecord->update();

        // $kitchenRecord->update($request->all());

        return redirect()->route('kitchen.index')->with('success', 'Kitchen record updated successfully.');
    }

    public function destroy(Request $request)
    {
        $kitchenRecord = KitchenRecord::where('id', $request->id)->first();
        $kitchenRecord->delete();
        return redirect()->route('kitchen.index')->with('success', 'Kitchen record deleted successfully.');
    }

    public function kitchenReports(Request $request)
    {
        $kitchenRecords = KitchenRecord::all();

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $kitchenRecords = KitchenRecord::whereBetween('date', [$startDate, $endDate])->get();

        }
        return view('kitchen.report', compact('kitchenRecords'));
    }
}
