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
use App\Http\Controllers\AssetController;
use App\Http\Controllers\PartController;

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
        });
        
        Route::resource('assets', AssetController::class);
        Route::resource('assets.parts', PartController::class)->except(['show']);
        Route::get('assets/parts', [PartController::class, 'index'])->name('assets.parts.index');

        

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
        Route::patch('purchase-orders/{id}/mark-as-billed', [PurchaseOrderController::class, 'markAsBilled'])->name('purchase.orders.markAsBilled');
        Route::patch('purchase-orders/{id}/update-status', [PurchaseOrderController::class, 'updateStatus'])->name('purchase.orders.updateStatus');
        Route::patch('purchase-orders/{id}/update-billed', [PurchaseOrderController::class, 'updateBilled'])->name('purchase.orders.updateBilled');
        Route::post('purchase-orders/{id}/receive-docket', [PurchaseOrderController::class, 'receiveDocket'])->name('purchase.orders.receiveDocket');

    });
});