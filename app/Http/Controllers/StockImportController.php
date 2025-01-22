<?php
namespace App\Http\Controllers;

use App\Models\StockImport;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StockImportImport;

class StockImportController extends Controller
{
    public function index()
    {
        // Fetch all stock imports from the database
        $imports = StockImport::all(); // This will get all stock imports

        // Pass the $imports variable to the view
        return view('inventory.stock_imports.index', compact('imports'));
    }

    public function create()
    {
        $warehouses = Warehouse::all();
        return view('inventory.stock_imports.create', compact('warehouses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'warehouse_id' => 'required',
            'file' => 'required|file|mimes:xlsx,csv',
        ]);

        $file = $request->file('file');
        $path = $file->storeAs('uploads', time() . '_' . $file->getClientOriginalName());

        // Import the file
        Excel::import(new StockImportImport, storage_path('app/' . $path));

        StockImport::create([
            'warehouse_id' => $request->warehouse_id,
            'file_path' => $path,
        ]);

        return redirect()->route('stock-imports.index')->with('success', 'Stock imported successfully.');
    }

    public function show(StockImport $import)
    {
        return view('inventory.stock-imports.show', compact('import'));
    }

    public function destroy(StockImport $import)
    {
        $import->delete();
        return redirect()->route('inventory.stock-imports.index');
    }
}
