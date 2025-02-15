<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Part;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class AssetController extends Controller
{
    // Display a listing of the assets
    public function index()
    {
        $assets = Asset::with('parts')->get();
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
                $asset->products()->attach($product->id, ['quantity' => $request->quantity, 'req_by' => $request->req_by, 'rec_by' => $request->rec_by]);
                $product->stock -= $request->quantity;
                $product->save();
            } else {
                return redirect()->back()->withErrors(['error' => 'Not enough stock for ' . $product->name]);
            }
        // }

        return redirect()->route('assets.index')->with('success', 'Parts allocated successfully.');
    }

    public function partsReport($id)
    {
        $asset = Asset::with(['parts' => function ($query) {
            $query->with('product');
        }])->findOrFail($id);
        $reportData = DB::select("
            SELECT 
                assets.id AS asset_id,
                assets.asset_name,
                assets.description AS asset_description,
                assets.value AS asset_value,
                assets.status AS asset_status,
                products.id AS product_id,
                products.name AS product_name,
                (SELECT AVG(purchase_order_items.price) 
                 FROM purchase_order_items 
                 WHERE purchase_order_items.product_id = products.id) AS avg_product_price,
                asset_part.quantity AS product_quantity,
                asset_part.req_by AS req_by,
                asset_part.rec_by AS rec_by
            FROM 
                assets
            JOIN 
                asset_part ON assets.id = asset_part.asset_id
            JOIN 
                products ON asset_part.product_id = products.id
            WHERE 
                assets.id = $id
            ORDER BY 
                assets.asset_name, products.name
        ");
        return view('assets.parts_report', compact('asset','reportData'));
    }

    public function assetsReport(Request $request)
    {
        $fromDate = $request->from_date . ' 00:00:00';
        $toDate = $request->to_date . ' 23:59:59';
        $st = $request->status;

        $asset = Asset::with(['parts' => function ($query) {
            $query->with('product');
        }]);
        $reportData = DB::select("
            SELECT 
                assets.id AS asset_id,
                assets.asset_name,
                assets.description AS asset_description,
                assets.value AS asset_value,
                assets.status AS asset_status,
                products.id AS product_id,
                products.name AS product_name,
                products.price AS product_price,
                (SELECT AVG(purchase_order_items.price) 
                 FROM purchase_order_items 
                 WHERE purchase_order_items.product_id = products.id) AS avg_product_price,
                asset_part.quantity AS product_quantity,
                asset_part.req_by AS req_by,
                asset_part.rec_by AS rec_by
            FROM 
                assets
            JOIN 
                asset_part ON assets.id = asset_part.asset_id
            JOIN 
                products ON asset_part.product_id = products.id
            WHERE
                date(asset_part.created_at) BETWEEN '$fromDate' AND '$toDate'
            ORDER BY 
                assets.asset_name, products.name
        ");
        // dd($reportData);
        return view('assets.reports', compact('asset','reportData'));
    }

}
