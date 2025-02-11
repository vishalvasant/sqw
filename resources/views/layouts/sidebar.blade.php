@php
    $usr = Auth::guard('web')->user();
 @endphp
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/" class="brand-link">
        <!-- <img src="{{ asset('adminlte/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8"> -->
        <span class="brand-text font-weight-light">SHREEJI QUARRY WORKS</span>
    </a>

    <!-- Sidebar Menu -->
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
                <!-- Inventory Module -->
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>
                            Inventory
                            <i class="right fas fa-angle-left"></i>
                        </p>
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
                        <li class="nav-item">
                            <a href="{{ route('inventory.categories.index') }}" class="nav-link">
                                <i class="fas fa-th-large nav-icon"></i>
                                <p>Categories</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('inventory.units.index') }}" class="nav-link">
                                <i class="fas fa-ruler nav-icon"></i>
                                <p>Units</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('inventory.warehouses.index') }}" class="nav-link">
                                <i class="fas fa-building nav-icon"></i>
                                <p>Warehouses</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('inventory.stock-imports.index') }}" class="nav-link">
                                <i class="fas fa-arrow-circle-up nav-icon"></i>
                                <p>Stock Imports</p>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <!-- Purchase Management Module -->
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-shopping-cart"></i>
                        <p>Purchase<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('purchase.suppliers.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Suppliers</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('purchase.requests.index') }}" class="nav-link">
                                <i class="fas fa-list nav-icon"></i>
                                <p>Purchase Requests-Orders</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('purchase.orders.index') }}" class="nav-link">
                                <i class="fas fa-file-invoice nav-icon"></i>
                                <p>Stock In</p>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <!-- Fleet Management Module -->
                <li class="nav-item has-treeview">
                    <a href="{{ route('fleet.vehicles.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-truck"></i>
                        <p>Fleet Management<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('fleet.vehicles.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-car"></i>
                                <p>Vehicles</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('fleet.drivers') }}" class="nav-link">
                                <i class="nav-icon fas fa-user"></i>
                                <p>Drivers</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('fleet.fuel_usages.index') }}" class="nav-link {{ request()->routeIs('fuel_usages.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-gas-pump"></i>
                                <p>Fuel Usages</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('fleet.reports') }}" class="nav-link">
                                <i class="nav-icon fas fa-chart-line"></i>
                                <p>Reports</p>
                            </a>
                        </li>
                    </ul>
                </li>

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
                
                <!-- User and Role Permission Management Module -->
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa fa-users"></i>
                        <p>
                            User Management
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('users.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Users</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('roles.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-user-tag"></i>
                                <p>Roles</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('permissions.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-key"></i>
                                <p>Permissions</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fa fa-home nav-icon"></i>
                    <p>Logout</p>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </a>
                </li>
                
            </ul>
        </nav>
    </div>
</aside>
