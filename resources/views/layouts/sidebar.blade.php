<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="index.html">RT</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="index.html">St</a>
        </div>
        {{-- {% include "layouts/menu.html" %} --}}
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            @auth
                @if (auth()->user()->role === 'manager')
                    <li><a class="nav-link" href="{{ route('category.index') }}">
                            <i class="fas fa-th-large"></i> <span>Categories</span></a>
                    </li>
                    <li><a class="nav-link" href="{{ route('product.index') }}">
                            <i class="fas fa-th-large"></i> <span>Products</span></a>
                    </li>
                    <li>
                        <a class="nav-link" href="#">
                            <i class="fas fa-th-large"></i> <span>Customers</span></a>
                    </li>
                @endif
            @endauth


            {{-- login cashier --}}
            @if (auth()->check() && auth()->user()->role === 'cashier')
                <li><a class="nav-link" href="{{ route('sales.index') }}">
                        <i class="fas fa-th-large"></i> <span>Sales</span></a>
                </li>
            @endif


        </ul>
    </aside>
</div>
