@php
    $usr = Auth::guard('web')->user();
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
                    <a href="{{ route('home') }}" class="nav-link">
                        <i class="fa fa-home nav-icon"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                @endif
                
                @if ($usr->can('products.view') || $usr->can('categories.view') || $usr->can('units.view'))
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>Inventory<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if ($usr->can('products.view'))
                        <li class="nav-item">
                            <a href="{{ route('inventory.products.index') }}" class="nav-link">
                                <i class="fas fa-cube nav-icon"></i>
                                <p>Products</p>
                            </a>
                        </li>
                        @endif
                        @if ($usr->can('categories.view'))
                        <li class="nav-item">
                            <a href="{{ route('inventory.categories.index') }}" class="nav-link">
                                <i class="fas fa-th-large nav-icon"></i>
                                <p>Categories</p>
                            </a>
                        </li>
                        @endif
                        @if ($usr->can('units.view'))
                        <li class="nav-item">
                            <a href="{{ route('inventory.units.index') }}" class="nav-link">
                                <i class="fas fa-ruler nav-icon"></i>
                                <p>Units</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif
                
                @if ($usr->can('suppliers.view') || $usr->can('requests.view') || $usr->can('orders.view'))
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-shopping-cart"></i>
                        <p>Purchase<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if ($usr->can('suppliers.view'))
                        <li class="nav-item">
                            <a href="{{ route('purchase.suppliers.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Suppliers</p>
                            </a>
                        </li>
                        @endif
                        @if ($usr->can('requests.view'))
                        <li class="nav-item">
                            <a href="{{ route('purchase.requests.index') }}" class="nav-link">
                                <i class="fas fa-list nav-icon"></i>
                                <p>Purchase Requests-Orders</p>
                            </a>
                        </li>
                        @endif
                        @if ($usr->can('orders.view'))
                        <li class="nav-item">
                            <a href="{{ route('purchase.orders.index') }}" class="nav-link">
                                <i class="fas fa-file-invoice nav-icon"></i>
                                <p>Stock In</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif
                
                @if ($usr->can('fuel_usages.view') || $usr->can('vehicles.view') || $usr->can('drivers.view') || $usr->can('reports.view'))
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-truck"></i>
                        <p>Fleet Management<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if ($usr->can('vehicles.view'))
                        <li class="nav-item">
                            <a href="{{ route('fleet.vehicles.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-car"></i>
                                <p>Vehicles</p>
                            </a>
                        </li>
                        @endif
                        @if ($usr->can('drivers.view'))
                        <li class="nav-item">
                            <a href="{{ route('fleet.drivers') }}" class="nav-link">
                                <i class="nav-icon fas fa-user"></i>
                                <p>Drivers</p>
                            </a>
                        </li>
                        @endif
                        @if ($usr->can('fuel_usages.view'))
                        <li class="nav-item">
                            <a href="{{ route('fleet.fuel_usages.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-gas-pump"></i>
                                <p>Fuel Usages</p>
                            </a>
                        </li>
                        @endif
                        @if ($usr->can('reports.view'))
                        <li class="nav-item">
                            <a href="{{ route('fleet.reports') }}" class="nav-link">
                                <i class="nav-icon fas fa-chart-line"></i>
                                <p>Reports</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

                <!-- Asset Management Module -->
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>
                            Asset Management
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('assets.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Assets</p>
                            </a>
                        </li>
                    </ul>
                </li>
                

                <li class="nav-item">
                    <a href="{{ route('tasks.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-tasks"></i>
                        <p>Task Management</p>
                    </a>
                </li>

                
                @if ($usr->can('users.view') || $usr->can('roles.view') || $usr->can('permissions.view'))
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa fa-users"></i>
                        <p>User Management<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if ($usr->can('users.view'))
                        <li class="nav-item">
                            <a href="{{ route('users.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Users</p>
                            </a>
                        </li>
                        @endif
                        @if ($usr->can('roles.view'))
                        <li class="nav-item">
                            <a href="{{ route('roles.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-user-tag"></i>
                                <p>Roles</p>
                            </a>
                        </li>
                        @endif
                        @if ($usr->can('permissions.view'))
                        <li class="nav-item">
                            <a href="{{ route('permissions.index') }}" class="nav-link">
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
