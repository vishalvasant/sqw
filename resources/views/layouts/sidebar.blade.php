@php
    $usr = Auth::guard('web')->user();
    $currentRoute = request()->route()->getName();
    $routeSegments = explode('.', $currentRoute);
    $currentPrefix = $routeSegments[0] ?? '';
    
    // Function to check if a menu item is active
    $isActive = function($routeName, $section = null) use ($currentRoute, $routeSegments) {
        // For main menu items
        if ($section) {
            return isset($routeSegments[0]) && $routeSegments[0] === $section ? 'active' : '';
        }
        // For submenu items - exact match or parent route
        return $currentRoute === $routeName || 
               (strpos($currentRoute, $routeName . '.') === 0) ? 'active' : '';
    };
    
    // Function to check if a menu should be open
    $isMenuOpen = function($sections) use ($routeSegments) {
        if (is_string($sections)) {
            $sections = [$sections];
        }
        
        foreach ($sections as $section) {
            if (isset($routeSegments[0]) && $routeSegments[0] === $section) {
                return 'menu-open';
            }
        }
        
        return '';
    };
    
    // Function to check if a main menu item is active
    $isMainMenuActive = function($section) use ($routeSegments) {
        return isset($routeSegments[0]) && $routeSegments[0] === $section ? 'active' : '';
    };
@endphp
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <div class="text-center">
        <a href="/" class="brand-link">
            <span class="brand-text center">SHREEJI QUARRY WORKS</span>
        </a>
    </div>
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @if ($usr->can('dashboard.view'))
                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link {{ $isActive('home') }}">
                        <i class="fas fa-tachometer-alt nav-icon"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                @endif
                
                @if ($usr->can('products.view') || $usr->can('categories.view') || $usr->can('units.view'))
                <li class="nav-item has-treeview {{ $isMenuOpen('inventory') }}">
                    <a href="#" class="nav-link {{ $isMainMenuActive('inventory') }}">
                        <i class="nav-icon fas fa-cubes"></i>
                        <p>Inventory<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if ($usr->can('products.view'))
                        <li class="nav-item">
                            <a href="{{ route('inventory.products.index') }}" class="nav-link {{ $isActive('inventory.products.index') }}">
                                <i class="fas fa-cube nav-icon"></i>
                                <p>Products</p>
                            </a>
                        </li>
                        @endif
                        @if ($usr->can('categories.view'))
                        <li class="nav-item">
                            <a href="{{ route('inventory.categories.index') }}" class="nav-link {{ $isActive('inventory.categories.index') }}">
                                <i class="fas fa-th-large nav-icon"></i>
                                <p>Categories</p>
                            </a>
                        </li>
                        @endif
                        @if ($usr->can('units.view'))
                        <li class="nav-item">
                            <a href="{{ route('inventory.units.index') }}" class="nav-link {{ $isActive('inventory.units.index') }}">
                                <i class="fas fa-ruler nav-icon"></i>
                                <p>Units</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif
                
                @if ($usr->can('suppliers.view') || $usr->can('requests.view') || $usr->can('orders.view'))
                <li class="nav-item has-treeview {{ $isMenuOpen('purchase') }}">
                    <a href="#" class="nav-link {{ $isMainMenuActive('purchase') }}">
                        <i class="nav-icon fas fa-shopping-cart"></i>
                        <p>Purchase<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if ($usr->can('suppliers.view'))
                        <li class="nav-item">
                            <a href="{{ route('purchase.suppliers.index') }}" class="nav-link {{ $isActive('purchase.suppliers.index') }}">
                                <i class="fas fa-parachute-box nav-icon"></i>
                                <p>Suppliers</p>
                            </a>
                        </li>
                        @endif
                        @if ($usr->can('requests.view'))
                        <li class="nav-item">
                            <a href="{{ route('purchase.requests.index') }}" class="nav-link {{ $isActive('purchase.requests.index') }}">
                                <i class="fas fa-print nav-icon"></i>
                                <p>Purchase Requests-Orders</p>
                            </a>
                        </li>
                        @endif
                        @if ($usr->can('orders.view'))
                        <li class="nav-item">
                            <a href="{{ route('purchase.orders.index') }}" class="nav-link {{ $isActive('purchase.orders.index') }}">
                                <i class="fas fa-file-invoice nav-icon"></i>
                                <p>Stock In</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

                @if ($usr->can('services.view') || $usr->can('services_request.view') || $usr->can('services_order.view') || $usr->can('vendors.view'))
                @php
                    $servicesMenuOpen = strpos($currentRoute, 'product_services.') === 0 || 
                                     strpos($currentRoute, 'service_pr.') === 0 || 
                                     strpos($currentRoute, 'service_po.') === 0 ||
                                     strpos($currentRoute, 'vendors.') === 0 ||
                                     strpos($currentRoute, 'service') === 0;
                @endphp
                <li class="nav-item has-treeview {{ $servicesMenuOpen ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ $servicesMenuOpen ? 'active' : '' }}">
                        <i class="nav-icon fas fa-recycle"></i>
                        <p>Services<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if ($usr->can('services.view'))
                        <li class="nav-item">
                            <a href="{{ route('product_services.index') }}" class="nav-link {{ $isActive('product_services.index') || $currentRoute === 'product_services.create' || $currentRoute === 'product_services.edit' || strpos($currentRoute, 'product_services.') === 0 ? 'active' : '' }}">
                                <i class="fas fa-parachute-box nav-icon"></i>
                                <p>New Services</p>
                            </a>
                        </li>
                        @endif
                        @if ($usr->can('services_request.view'))
                        <li class="nav-item">
                            <a href="{{ route('service_pr.index') }}" class="nav-link {{ $isActive('service_pr.index') || strpos($currentRoute, 'service_pr.') === 0 ? 'active' : '' }}">
                                <i class="fas fa-print nav-icon"></i>
                                <p>Service Requests-Orders</p>
                            </a>
                        </li>
                        @endif
                        @if ($usr->can('services_order.view'))
                        <li class="nav-item">
                            <a href="{{ route('service_po.index') }}" class="nav-link {{ $isActive('service_po.index') || strpos($currentRoute, 'service_po.') === 0 ? 'active' : '' }}">
                                <i class="fas fa-file-invoice nav-icon"></i>
                                <p>Service Utilize</p>
                            </a>
                        </li>
                        @endif
                        @if ($usr->can('vendors.view'))
                        <li class="nav-item">
                            <a href="{{ route('vendors.index') }}" class="nav-link {{ $isActive('vendors.index') || strpos($currentRoute, 'vendors.') === 0 ? 'active' : '' }}">
                                <i class="fas fa-user nav-icon"></i>
                                <p>Vendors</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

                @if ($usr->can('fuel_usages.view') || $usr->can('vehicles.view') || $usr->can('drivers.view') || $usr->can('view_reports'))
                @php
                    $fleetMenuOpen = strpos($currentRoute, 'vehicles.') === 0 || 
                                  strpos($currentRoute, 'fleet.') === 0 ||
                                  $currentRoute === 'fleet.drivers' ||
                                  $currentRoute === 'fleet.reports';
                @endphp
                <li class="nav-item has-treeview {{ $fleetMenuOpen ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ $fleetMenuOpen ? 'active' : '' }}">
                        <i class="nav-icon fas fa-truck"></i>
                        <p>Fleet Management<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if ($usr->can('vehicles.view'))
                        <li class="nav-item">
                            <a href="{{ route('vehicles.index') }}" class="nav-link {{ strpos($currentRoute, 'vehicles.') === 0 ? 'active' : '' }}">
                                <i class="nav-icon fas fa-car"></i>
                                <p>Vehicles</p>
                            </a>
                        </li>
                        @endif
                        @if ($usr->can('drivers.view'))
                        <li class="nav-item">
                            <a href="{{ route('fleet.drivers') }}" class="nav-link {{ $currentRoute === 'fleet.drivers' ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user"></i>
                                <p>Drivers</p>
                            </a>
                        </li>
                        @endif
                        @if ($usr->can('fuel_usages.view'))
                        <li class="nav-item">
                            <a href="{{ route('fleet.fuel_usages.index') }}" class="nav-link {{ strpos($currentRoute, 'fleet.fuel_usages.') === 0 ? 'active' : '' }}">
                                <i class="nav-icon fas fa-gas-pump"></i>
                                <p>Fuel Usages</p>
                            </a>
                        </li>
                        @endif
                        @if ($usr->can('reports.view'))
                        <li class="nav-item">
                            <a href="{{ route('fleet.reports') }}" class="nav-link {{ $currentRoute === 'fleet.reports' ? 'active' : '' }}">
                                <i class="nav-icon fas fa-chart-line"></i>
                                <p>Reports</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

                @if ($usr->can('assets.view'))
                <li class="nav-item has-treeview {{ $isMenuOpen('assets') }}">
                    <a href="#" class="nav-link {{ $isMainMenuActive('assets') }}">
                        <i class="nav-icon fas fa-truck-loading"></i>
                        <p>
                            Asset Management
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('assets.index') }}" class="nav-link {{ $isActive('assets', 'assets') }}">
                                <i class="fas fa-truck nav-icon"></i>
                                <p>Assets</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
                
                @if($usr->can('tasks.view'))
                <li class="nav-item">
                    <a href="{{ route('tasks.index') }}" class="nav-link {{ $isActive('tasks', 'tasks') }}">
                        <i class="nav-icon fas fa-tasks"></i>
                        <p>Task Management</p>
                    </a>
                </li>
                @endif

                @if($usr->can('kitchen.view'))
                <li class="nav-item">
                    <a href="{{ route('kitchen.index') }}" class="nav-link {{ $isActive('kitchen', 'kitchen') }}">
                        <i class="nav-icon fas fa-utensils"></i>
                        <p>Kitchen</p>
                    </a>
                </li>
                @endif

                @if ($usr->can('reports.view'))
                <li class="nav-item has-treeview {{ $isMenuOpen('reports') }}">
                    <a href="#" class="nav-link {{ $isMainMenuActive('reports') }}">
                        <i class="nav-icon fas fa-chart-bar"></i>
                        <p>Reports<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('reports.ledger.index') }}" class="nav-link {{ $isActive('reports.ledger', 'reports') }}">
                                <i class="fas fa-book nav-icon"></i>
                                <p>Product Ledger</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                @if ($usr->can('users.view') || $usr->can('roles.view') || $usr->can('permissions.view'))
                <li class="nav-item has-treeview {{ $isMenuOpen(['users', 'roles', 'permissions']) }}">
                    <a href="#" class="nav-link {{ $isMainMenuActive('users') || $isMainMenuActive('roles') || $isMainMenuActive('permissions') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-users"></i>
                        <p>User Management<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if ($usr->can('users.view'))
                        <li class="nav-item">
                            <a href="{{ route('users.index') }}" class="nav-link {{ $isActive('users', 'users') }}">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Users</p>
                            </a>
                        </li>
                        @endif
                        @if ($usr->can('roles.view'))
                        <li class="nav-item">
                            <a href="{{ route('roles.index') }}" class="nav-link {{ $isActive('roles', 'roles') }}">
                                <i class="nav-icon fas fa-user-tag"></i>
                                <p>Roles</p>
                            </a>
                        </li>
                        @endif
                        @if ($usr->can('permissions.view'))
                        <li class="nav-item">
                            <a href="{{ route('permissions.index') }}" class="nav-link {{ $isActive('permissions', 'permissions') }}">
                                <i class="nav-icon fas fa-key"></i>
                                <p>Permissions</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif
            </ul>
        </nav>
    </div>
</aside>
