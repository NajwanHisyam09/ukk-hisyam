@auth
<div class="main-sidebar sidebar-style-2" style="background-color: #2c3e50; color: #ecf0f1;">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="" style="color: #ecf0f1; font-size: 24px;">Web Kasir</a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header" style="color: #ecf0f1;">Dashboard</li>
            <li class="{{ Request::is('home') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('home') }}" style="color: #ecf0f1;"><i class="fas fa-fire"></i><span>Dashboard</span></a>
            </li>

            @if(in_array(auth()->user()->role, ['admin', 'manageradmin']))

            <li class="menu-header" style="color: #ecf0f1;">Menu</li>
            <li class="{{ Request::is('product') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('products.index') }}" style="color: #ecf0f1;"><i class="fas fa-shopping-bag"></i> <span>Produk</span></a>
            </li>
            <li class="{{ Request::is('sales') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('sales.index') }}" style="color: #ecf0f1;"><i class="fas fa-shopping-cart"></i> <span>Penjualan</span></a>
            </li>
            <li class="menu-header" style="color: #ecf0f1;">User </li>
            <li class="{{ Request::is('user') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('user.index')}}" style="color: #ecf0f1;"><i class="fas fa-user-shield"></i> <span>User</span></a>
            </li>
            <li class="{{ Request::is('members') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('members.index')}}" style="color: #ecf0f1;"><i class="fas fa-user"></i> <span>Member</span></a>
            </li>
            @endif

            @if (Auth::user()->role == 'user')
            <li class="menu-header" style="color: #ecf0f1;">Menu</li>
            <li class="{{ Request::is('product') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('products.index') }}" style="color: #ecf0f1;"><i class="fas fa-shopping-bag"></i> <span>Produk</span></a>
            </li>
            <li class="{{ Request::is('sales') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('sales.index') }}" style="color: #ecf0f1;"><i class="fas fa-shopping-cart"></i> <span>Penjualan</span></a>
            </li>
            <li class="menu-header" style="color: #ecf0f1;">User </li>
            <li class="{{ Request::is('members') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('members.index')}}" style="color: #ecf0f1;"><i class=" fas fa-user"></i> <span>Member</span></a>
            </li>
            @endif
        </ul>
    </aside>
</div>
@endauth

<style>
    .nav-link:hover {
        background-color: #34495e;
        transition: background-color 0.3s ease;
    }
    .active {
        background-color: #2980b9;
    }
</style>
