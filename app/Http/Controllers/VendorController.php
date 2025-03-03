<?php
namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\ServicePurchaseOrder;

class VendorController extends Controller
{
    public function index()
    {
        $vendors = Vendor::latest()->paginate(10);
        return view('vendors.index', compact('vendors'));
    }

    public function create()
    {
        return view('vendors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:vendors,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        Vendor::create($request->all());
        return redirect()->route('vendors.index')->with('success', 'Vendor created successfully.');
    }

    public function show(Vendor $vendor)
    {
        return view('vendors.show', compact('vendor'));
    }

    public function edit(Vendor $vendor)
    {
        return view('vendors.edit', compact('vendor'));
    }

    public function update(Request $request, Vendor $vendor)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:vendors,email,' . $vendor->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $vendor->update($request->all());
        return redirect()->route('vendors.index')->with('success', 'Vendor updated successfully.');
    }

    public function destroy(Vendor $vendor)
    {
        $vendor->delete();
        return redirect()->route('vendors.index')->with('success', 'Vendor deleted successfully.');
    }

    public function report(Request $request)
    {
        $vendors = Vendor::all();
        $servicePOs = collect(); // Empty collection by default

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();

            // Fetch Service Purchase Orders within date range
            $servicePOs = ServicePurchaseOrder::with('vendor','items', 'items.service')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get();
        }

        return view('vendors.report', compact('vendors', 'servicePOs'));
    }
}
