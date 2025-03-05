<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
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
use App\Http\Controllers\TaskController;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use App\Http\Controllers\ProductServiceController;
use App\Http\Controllers\ServicePurchaseRequestController;
use App\Http\Controllers\ServicePurchaseOrderController;
use App\Http\Controllers\VendorController;


Route::get('/', function () {
    return view('landing');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/purchase-requests/{id}/details', [PurchaseRequestController::class, 'getDetails']);

Route::middleware(['auth'])->group(function () {
    Route::resource('product_services', ProductServiceController::class);
    Route::resource('service_pr', ServicePurchaseRequestController::class);
    Route::get('/reports', [ServicePurchaseRequestController::class, 'servicePurchaseRequestsReport'])->name('service_pr.report');
    Route::resource('service_po', ServicePurchaseOrderController::class);
    Route::patch('service_po/{id}/update-status', [ServicePurchaseOrderController::class, 'updateStatus'])->name('service_po.updateStatus');
    Route::patch('service_po/{id}/update-billed', [ServicePurchaseOrderController::class, 'updateBilled'])->name('service_po.updateBilled');
    Route::get('/service-reports', [ServicePurchaseOrderController::class, 'purchaseServiceOrdersReport'])->name('service_po.report');
    Route::resource('vendors', VendorController::class);
    Route::get('/vendorReports', [VendorController::class, 'vendorReports'])->name('vendors.vendorReports');



    Route::resource('tasks', TaskController::class);
    Route::post('/tasks/{task}/delete-file', [TaskController::class, 'deleteFile'])->name('tasks.delete-file');
    Route::post('/tasks/{id}/approve', [TaskController::class, 'approveTask'])->name('tasks.approve');
    Route::get('/tasks.report', [TaskController::class, 'report'])->name('tasks.report');

    Route::get('/notifications/read-all', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    })->name('notifications.markAllAsRead');

    Route::prefix('admin')->group(function () {
            // User Management
        Route::resource('users', UserController::class);

        // Role Management
        Route::resource('roles', RoleController::class)->except(['show']);
        Route::post('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
        Route::post('/roles/{role}/permissions', [RoleController::class, 'updatePermissions'])->name('roles.permissions.update');

        
        // Permission Management
        Route::resource('permissions', PermissionController::class)->except(['show']);
        Route::get('/permissions/{role}', [PermissionController::class, 'getRolePermissions'])->name('permissions.getRolePermissions');
        Route::put('/permissions/update', [PermissionController::class, 'update'])->name('permissions.update');
        

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
        Route::post('assets.allocateservice', [AssetController::class, 'allocateService'])->name('assets.allocateservice');
        Route::post('assets.parts.remove', [AssetController::class, 'allocateRemove'])->name('assets.parts.remove');
        Route::post('assets.services.remove', [AssetController::class, 'allocateRemoveService'])->name('assets.services.remove');
        Route::get('assets/{id}/parts-report', [AssetController::class, 'partsReport'])->name('assets.parts.report');
        Route::resource('assets.parts', PartController::class)->except(['show']);
        Route::get('assets.parts.index', [AssetController::class, 'show'])->name('assets.parts.index');
        Route::get('assets.reports', [AssetController::class, 'assetsReport'])->name('assets.reports');
        Route::get('assets/serciceallocate/{asset}', [AssetController::class, 'serciceAllocate'])->name('assets.serciceallocate');

        

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

        Route::get('/reports', [PurchaseRequestController::class, 'purchaseRequestsReport'])->name('purchase.requests.report');
        Route::prefix('purchase')->name('purchase.')->middleware('auth')->group(function () {
            // Purchase Requests
            Route::resource('requests', PurchaseRequestController::class);
            
            
            // Purchase Orders
            Route::resource('orders', PurchaseOrderController::class);
            Route::get('/reports', [PurchaseOrderController::class, 'purchaseOrdersReport'])->name('orders.report');

            // Supplier
            Route::resource('suppliers', SupplierController::class);
        });
        Route::get('/products/utilization/{id}', [ProductController::class, 'productUtilization'])->name('products.utilization.report');
        Route::get('/products/report', [ProductController::class, 'generateReport'])->name('inventory.products.report');

        Route::patch('purchase-orders/{id}/mark-as-billed', [PurchaseOrderController::class, 'markAsBilled'])->name('purchase.orders.markAsBilled');
        Route::patch('purchase-orders/{id}/update-status', [PurchaseOrderController::class, 'updateStatus'])->name('purchase.orders.updateStatus');
        Route::patch('purchase-orders/{id}/update-billed', [PurchaseOrderController::class, 'updateBilled'])->name('purchase.orders.updateBilled');
        Route::post('purchase-orders/{id}/receive-docket', [PurchaseOrderController::class, 'receiveDocket'])->name('purchase.orders.receiveDocket');
        Route::get('fleet/vehicles/{id}/utilization', [FleetController::class, 'utilization'])->name('fleet.vehicles.utilization');
        Route::get('fleet/utilization-report', [FleetController::class, 'generateUtilizationReport'])->name('fleet.utilization.report');
        
    
    });
});