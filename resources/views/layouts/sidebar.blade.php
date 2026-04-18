<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
        <a href="./index.html" class="brand-link"></a>
    </div>

    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                <li class="nav-item ">
                    <a href="{{ route('dashboard.index') }}" class="nav-link">
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('suppliers.index') }}" class="nav-link">
                        <i class="nav-icon bi bi-people"></i>
                        <p>Suppliers</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('products.index') }}" class="nav-link">
                        <i class="nav-icon bi bi-box-seam-fill"></i>
                        <p>Products</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('warehouse.orders.index') }}" class="nav-link">
                        <i class="nav-icon bi bi-clipboard-fill"></i>
                        <p>Warehouse Order</p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-truck"></i>
                        <p>
                            Goods Receiving
                            <i class="right bi bi-chevron-down"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">

                        <li class="nav-item">
                            <a href="{{ route('goods.receiving.index') }}" class="nav-link">
                                <i class="bi bi-circle nav-icon"></i>
                                <p>Goods Receiving</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('manual-invoices.index') }}" class="nav-link">
                                <i class="bi bi-circle nav-icon"></i>
                                <p>Manual Invoice</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('returns.list') }}" class="nav-link">
                                <i class="bi bi-circle nav-icon"></i>
                                <p>Returns</p>
                            </a>
                        </li>

                    </ul>
                </li>


                <li class="nav-item">
                    <a href="{{ route('damaged-products.index') }}" class="nav-link">
                        <i data-lucide="package-x" class="nav-icon"></i>
                        <p>Damages</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('stocktake.index') }}" class="nav-link">
                        <i class="nav-icon bi bi-clipboard-check"></i>
                        <p>Stocktake</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('accounts.index') }}" class="nav-link">
                        <i class="nav-icon bi bi-wallet2"></i>
                        <p>Accounts</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('sales.index') }}" class="nav-link">
                        <i class="nav-icon bi bi-currency-dollar"></i>
                        <p>Sales</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-bar-chart-line"></i>
                        <p>Reports</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>