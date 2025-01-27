<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\StockImportController;
use App\Http\Controllers\Inventory\ProductController;
use App\Http\Controllers\Inventory\CategoryController;
use App\Http\Controllers\Inventory\UnitController;
use App\Http\Controllers\Purchase\PurchaseRequestController;
use App\Http\Controllers\Purchase\PurchaseOrderController;
use App\Http\Controllers\Purchase\SupplierController;
use App\Http\Controllers\Fleet\FleetController;
use App\Http\Controllers\Fleet\DriverController;
use App\Http\Controllers\Fleet\FuelLogController;
use App\Http\Controllers\Fleet\TripLogController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\PartController;
use App\Http\Controllers\FuelUsageController;


Route::get('/', function () {
    return view('landing');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/purchase-requests/{id}/details', [PurchaseRequestController::class, 'getDetails']);

Route::middleware(['auth'])->group(function () {
    Route::prefix('admin')->group(function () {
        // User Management
        Route::resource('users', UserController::class);

        // Role Management
        Route::resource('roles', RoleController::class);

        // Warehouse Routes
        Route::resource('warehouses', WarehouseController::class);

        // Stock Import Routes
        Route::resource('stock-imports', StockImportController::class);

        Route::prefix('fleet')->name('fleet.')->group(function () {
            Route::resource('vehicles', FleetController::class);
            Route::resource('drivers', FleetController::class);
            Route::get('drivers', [FleetController::class, 'showDrivers'])->name('drivers');
            Route::get('drivers.create', [FleetController::class, 'createDriver'])->name('drivers.create');
            Route::get('reports', [FleetController::class, 'generateReport'])->name('reports');
            Route::post('drivers.store', [FleetController::class, 'storeDriver'])->name('drivers.store');

            // Fleet Routes
            Route::resource('fuel_usages', FuelUsageController::class);

            // Route::resource('fleets', FleetController::class);

            // // Driver Routes
            // Route::resource('drivers', DriverController::class);

            // // Fuel Log Routes
            // Route::resource('fuel_logs', FuelLogController::class);

            // // Trip Log Routes
            // Route::resource('trip_logs', TripLogController::class);

        });
        
        Route::resource('assets', AssetController::class);
        Route::get('assets/{asset}', [AssetController::class, 'show'])->name('assets.show');
        Route::post('assets/{asset}/allocate', [AssetController::class, 'allocateParts'])->name('assets.parts.allocate');
        Route::get('assets/{id}/parts-report', [AssetController::class, 'partsReport'])->name('assets.parts.report');
        Route::resource('assets.parts', PartController::class)->except(['show']);
        Route::get('assets.parts.index', [AssetController::class, 'show'])->name('assets.parts.index');

        

        Route::prefix('inventory')->name('inventory.')->middleware('auth')->group(function () {
            // Warehouses
            Route::resource('warehouses', WarehouseController::class);
        
            // Stock Imports
            Route::resource('stock-imports', StockImportController::class);

            // Product Routes
            Route::resource('products', ProductController::class);

            // Category Routes
            Route::resource('categories', CategoryController::class);

            // Unit Routes
            Route::resource('units', UnitController::class);
        });

        Route::prefix('purchase')->name('purchase.')->middleware('auth')->group(function () {
            // Purchase Requests
            Route::resource('requests', PurchaseRequestController::class);
        
            // Purchase Orders
            Route::resource('orders', PurchaseOrderController::class);

            // Supplier
            Route::resource('suppliers', SupplierController::class);
        });
        Route::get('/products/utilization/{id}', [ProductController::class, 'productUtilization'])->name('products.utilization.report');

        Route::patch('purchase-orders/{id}/mark-as-billed', [PurchaseOrderController::class, 'markAsBilled'])->name('purchase.orders.markAsBilled');
        Route::patch('purchase-orders/{id}/update-status', [PurchaseOrderController::class, 'updateStatus'])->name('purchase.orders.updateStatus');
        Route::patch('purchase-orders/{id}/update-billed', [PurchaseOrderController::class, 'updateBilled'])->name('purchase.orders.updateBilled');
        Route::post('purchase-orders/{id}/receive-docket', [PurchaseOrderController::class, 'receiveDocket'])->name('purchase.orders.receiveDocket');

    });
});