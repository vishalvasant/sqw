<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Part;
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
        return view('assets.show', compact('asset'));
    }
}
