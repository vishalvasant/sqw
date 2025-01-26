<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Part;
use App\Models\Product;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    // Display a listing of the assets
    public function index()
    {
        $assets = Asset::all();
        return view('assets.index', compact('assets'));
    }

    // Show the form for creating a new asset
    public function create()
    {
        return view('assets.create');
    }

    // Store a newly created asset in storage
    public function store(Request $request)
    {
        $request->validate([
            'asset_name' => 'required|string|max:255',
            'value' => 'required|numeric',
            'purchase_date' => 'required|date',
        ]);

        Asset::create($request->all());
        return redirect()->route('assets.index');
    }

    // Display the specified asset
    public function show(Asset $asset)
    {
        $asset = Asset::findOrFail($asset->id);
        $availableParts = Product::all();
        return view('assets.parts.index', compact('asset','availableParts'));
    }

    public function assetParts()
    {
        $asset = Asset::all();
        return view('assets.parts.index', compact('asset'));
    }



    public function allocateParts(Request $request, $assetId)
    {
        $asset = Asset::findOrFail($assetId);
        // $products = $request->part_id;

        // foreach ($products as $productData) {
            $product = Product::findOrFail($request->part_id);
            
            // Check stock availability
            if ($product->stock >= $request->quantity) {
                // Allocate the product to the asset and deduct stock
                $asset->products()->attach($product->id, ['quantity' => $request->quantity]);
                $product->stock -= $request->quantity;
                $product->save();
            } else {
                return redirect()->back()->withErrors(['error' => 'Not enough stock for ' . $product->name]);
            }
        // }

        return redirect()->route('assets.index')->with('success', 'Parts allocated successfully.');
    }

}
