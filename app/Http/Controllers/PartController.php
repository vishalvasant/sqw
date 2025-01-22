<?php
namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Part; // Assuming Part is a model representing the available parts in inventory
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PartController extends Controller
{
    // Show parts allocated to a specific asset
    public function index(Asset $asset)
    {
        $parts = $asset->parts; // Assuming you have a relationship defined in Asset model for parts
        return view('assets.parts.index', compact('asset', 'parts'));
    }

    // Show form to allocate parts to the asset
    public function create(Asset $asset)
    {
        $availableParts = Part::all(); // Get all parts from the inventory
        return view('assets.parts.create', compact('asset', 'availableParts'));
    }

    // Store the allocated part to the asset
    public function store(Request $request, Asset $asset)
    {
        // Validate input
        $request->validate([
            'part_id' => 'required|exists:parts,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Allocate the part to the asset
        $asset->parts()->attach($request->part_id, ['quantity' => $request->quantity]);

        return redirect()->route('assets.parts.index', $asset->id)->with('success', 'Part allocated successfully.');
    }

    // Show form to edit the allocated part
    public function edit(Asset $asset, $partId)
    {
        $part = $asset->parts()->where('part_id', $partId)->first(); // Get the specific part allocated
        $availableParts = Part::all(); // Get all parts from inventory for the dropdown

        return view('assets.parts.edit', compact('asset', 'part', 'availableParts'));
    }

    // Update the part allocation
    public function update(Request $request, Asset $asset, $partId)
    {
        $request->validate([
            'part_id' => 'required|exists:parts,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Update part allocation
        $asset->parts()->updateExistingPivot($partId, ['quantity' => $request->quantity]);

        return redirect()->route('assets.parts.index', $asset->id)->with('success', 'Part updated successfully.');
    }

    // Remove part allocation
    public function destroy(Asset $asset, $partId)
    {
        // Remove the part allocation
        $asset->parts()->detach($partId);

        return redirect()->route('assets.parts.index', $asset->id)->with('success', 'Part removed successfully.');
    }
}

