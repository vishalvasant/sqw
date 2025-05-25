<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Part;
use App\Models\Product;
use App\Models\ProductService;
use App\Models\ServicePurchaseOrder;
use App\Models\AssetMaintenance;   
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

    public function edit(Asset $asset){
        return view('assets.edit', compact('asset'));
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

    public function update(Request $request){
        $request->validate([
            'asset_name' => 'required|string|max:255',
            'value' => 'required|numeric',
            'purchase_date' => 'required|date',
        ]);

        $asset = Asset::where('id', $request->id)->first();
        $asset->asset_name = $request->asset_name;
        $asset->value = $request->value;
        $asset->purchase_date = $request->purchase_date;
        $asset->description = $request->description;
        $asset->status = $request->status;
        $asset->update();
        return redirect()->route('assets.index');
    }

    // Display the specified asset
    public function show(Asset $asset)
    {
        $asset = Asset::findOrFail($asset->id);
        $availableParts = Product::all();
        return view('assets.parts.index', compact('asset','availableParts'));
    }

    public function serciceAllocate(Request $request, Asset $asset)
    {
        $selectedSO = null;
        if($request->request_id){
            $selectedSO = ServicePurchaseOrder::with('items.service')
            ->where('id', $request->request_id)
            ->get();
            // dd($selectedSO);
        }
        
        $asset = Asset::findOrFail($asset->id);
        $availableServices = ServicePurchaseOrder::with('items.service')
            ->whereHas('items.service', function($query) {
                $query->where('type', 'asset_allocation');
            })
            ->where('billed', 1)
            ->where('status', '!=', 'completed')
            ->get();                
        // $availableServices = ServicePurchaseOrder::with('items.service')
        //     ->where('billed', 1)
        //     ->where('status', '!=', 'completed')
        //     ->get();
        return view('assets.services.index', compact('asset','availableServices','selectedSO'));
    }

    public function maintenance(Request $request, Asset $asset)
    {
        $assetmaintenance = AssetMaintenance::with('asset')
            ->where('asset_id', $asset->id)
            ->get();
        $asset = Asset::findOrFail($asset->id);
        
        return view('assets.maintenance.index', compact('assetmaintenance','asset'));
    }

    public function createMaintenance(Request $request, Asset $asset)
    {
        $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'req_date' => 'required|date',
            'description' => 'required|string|max:255',
            'req_by' => 'required|string|max:255',
            'rec_by' => 'required|string|max:255',
        ]);
        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('log_files', 'public'); // Store file
        }

        AssetMaintenance::create(
            [
                'asset_id' => $request->asset_id,
                'req_date' => $request->req_date,
                'description' => $request->description,
                'req_by' => $request->req_by,
                'rec_by' => $request->rec_by,
                'file_path' => $filePath,
            ]
        );

         return redirect()->back()->with('success', 'Maintenance log created successfully.');
    }
  
    public function destroyMaintenance(Asset $asset, Request $request)
    {
        $assetmaintenance = AssetMaintenance::findOrFail($request->maintance_id);
        if ($assetmaintenance->file) {
            Storage::disk('public')->delete($assetmaintenance->file);
        }
        $assetmaintenance->delete();
        return redirect()->back()->with('success', 'Maintenance log deleted successfully.');
    }

    public function maintananceLog(Request $request, Asset $asset)
    {
        $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'req_date' => 'required|date',
            'description' => 'required|string|max:255',
            'req_by' => 'required|string|max:255',
            'rec_by' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        AssetMaintenance::create($request->all());
        return redirect()->route('assets.index')->with('success', 'Maintenance log created successfully.');
    }

    public function display(Asset $asset)
    {
        $asset = Asset::findOrFail($asset->id);
        $availableServices = ProductService::all();
        return view('assets.services.index', compact('asset','availableServices'));
    }

    public function assetParts()
    {
        $asset = Asset::all();
        return view('assets.parts.index', compact('asset'));
    }

    public function allocateService(Request $request)
    {
        $serviceOrder = ServicePurchaseOrder::with('items.service')->where('id',$request->service_id)->first();
        $asset = Asset::findOrFail($request->asset_id);
        $service = ProductService::findOrFail($serviceOrder->items[0]->service_id);
        $serviceOrder->status = 'completed';
        $serviceOrder->save();
        $asset->services()->attach($service->id, ['price'=> $request->s_amount, 'order_number'=>$request->s_id, 'description'=>$request->description, 'req_by' => $request->req_by, 'rec_by' => $request->rec_by]);
        return redirect()->route('assets.index')->with('success', 'Service allocated successfully.');
    }

    public function allocateParts(Request $request, $assetId)
    {
        $asset = Asset::findOrFail($assetId);
        if($request->part_id == null){
            // $serviceOrder = ServicePurchaseOrder::findOrFail($request->service_order_id);
            // $service = $serviceOrder->items()->where('service_id', $request->service_id)->firstOrFail()->service;
        }else{

            $product = Product::findOrFail($request->part_id);
            
            // Check stock availability
            if ($product->stock >= $request->quantity) {
                // Allocate the product to the asset and deduct stock
                $asset->products()->attach($product->id, ['quantity' => $request->quantity, 'description' => $request->description, 'req_by' => $request->req_by, 'rec_by' => $request->rec_by,'created_at'=>$request->created_at]);
                $product->stock -= $request->quantity;
                $product->save();
            } else {
                return redirect()->back()->withErrors(['error' => 'Not enough stock for ' . $product->name]);
            }
            return redirect()->route('assets.index')->with('success', 'Parts allocated successfully.');
        }
        // }

        
    }

    public function allocateRemove(Request $request)
    {
        // dd($request->all());

        $product = Product::findOrFail($request->product_id);
        $product->stock += $request->product_quantity;
        $product->save();
        DB::table('asset_part')->where('id', $request->asset_part_id)->delete();

        return redirect()->route('assets.index')->with('success', 'Parts deassociated successfully.');
    }

    public function allocateRemoveService(Request $request)
    {
        $servicePurchaseOrder = ServicePurchaseOrder::where('order_number',$request->asset_order_number)->get()->first();
        $servicePurchaseOrder->status = 'approved';
        $servicePurchaseOrder->update();
        DB::table('asset_service')->where('id', $request->asset_service_id)->delete();

        return redirect()->route('assets.index')->with('success', 'Service deassociated successfully.');
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
                asset_part.id AS asset_part_id,
                asset_part.quantity AS product_quantity,
                asset_part.description AS asset_part_description,
                asset_part.req_by AS req_by,
                asset_part.rec_by AS rec_by,
                asset_part.created_at AS asset_part_created_at
            FROM 
                assets
            JOIN 
                asset_part ON assets.id = asset_part.asset_id
            JOIN 
                products ON asset_part.product_id = products.id
            WHERE 
                assets.id = $id
            ORDER BY 
                asset_part_created_at,assets.asset_name, products.name
        ");
        return view('assets.parts_report', compact('asset','reportData'));
    }

    public function serviceReport($id)
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
                product_services.id AS product_id,
                product_services.name AS product_name,
                product_services.cost AS product_price,
                asset_service.id AS asset_service_id,
                asset_service.req_by AS req_by,
                asset_service.order_number AS asset_order_number,
                asset_service.description AS asset_service_description,
                asset_service.price AS asset_price,
                asset_service.rec_by AS rec_by,
                asset_service.created_at AS asset_service_created_at
            FROM 
                assets
            JOIN 
                asset_service ON assets.id = asset_service.asset_id
            JOIN 
                product_services ON asset_service.product_service_id = product_services.id
            WHERE
                assets.id = $id
            ORDER BY 
                asset_service_created_at,assets.asset_name, product_services.name
        ");
        return view('assets.service_report', compact('asset','reportData'));
    }

    public function assetsReport(Request $request)
    {
        $fromDate = $request->from_date . ' 00:00:00';
        $toDate = $request->to_date . ' 23:59:59';
        $st = $request->status;

        $asset = Asset::with(['parts' => function ($query) {
            $query->with('product');
        }]);
        if($request->type == 'parts'){
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
                asset_part.description AS asset_part_description,
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
        }else{

            $reportData = DB::select("
            SELECT 
                assets.id AS asset_id,
                assets.asset_name,
                assets.description AS asset_description,
                assets.value AS asset_value,
                assets.status AS asset_status,
                product_services.id AS product_id,
                product_services.name AS product_name,
                product_services.cost AS product_price,
                (SELECT AVG(service_purchase_order_items.unit_price) 
                 FROM service_purchase_order_items 
                 WHERE service_purchase_order_items.service_id = product_services.id) AS avg_product_price,
                asset_service.id AS asset_service_id,
                asset_service.req_by AS req_by,
                asset_service.order_number AS asset_order_number,
                asset_service.price AS asset_price,
                asset_service.rec_by AS rec_by
            FROM 
                assets
            JOIN 
                asset_service ON assets.id = asset_service.asset_id
            JOIN 
                product_services ON asset_service.product_service_id = product_services.id
            WHERE
                date(asset_service.created_at) BETWEEN '$fromDate' AND '$toDate'
            ORDER BY 
                assets.asset_name, product_services.name
        ");

        }
        
        // dd($reportData);
        return view('assets.reports', compact('asset','reportData'));
    }

}
