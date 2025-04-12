<!-- SidebarSearch Form -->
    <div class="form-inline mt-2">
        <div class="input-group" data-widget="sidebar-search">
            <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
                <button class="btn btn-sidebar">
                    <i class="fas fa-search fa-fw"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            {{-- Dashboard --}}
            <li class="nav-item">
                <a href="{{ url('/') }}" class="nav-link {{ $activeMenu == 'dashboard' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-tachometer-alt text-white"></i>
                    <p class="text-white">Dashboard</p>
                </a>
            </li>

            {{-- Data Pengguna --}}
            <li class="nav-header text-white">Data Pengguna</li>
            <li class="nav-item">
                <a href="{{ url('/level') }}" class="nav-link {{ $activeMenu == 'level' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-layer-group text-white"></i>
                    <p class="text-white">Level User</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/user') }}" class="nav-link {{ $activeMenu == 'user' ? 'active' : '' }}">
                    <i class="nav-icon far fa-user text-white"></i>
                    <p class="text-white">Data User</p>
                </a>
            </li>

            {{-- Data Barang --}}
            <li class="nav-header text-white">Data Barang</li>
            <li class="nav-item">
                <a href="{{ url('/kategori') }}" class="nav-link {{ $activeMenu == 'kategori' ? 'active' : '' }}">
                    <i class="nav-icon far fa-bookmark text-white"></i>
                    <p class="text-white">Kategori Barang</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/barang') }}" class="nav-link {{ $activeMenu == 'barang' ? 'active' : '' }}">
                    <i class="nav-icon far fa-list-alt text-white"></i>
                    <p class="text-white">Data Barang</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/supplier') }}" class="nav-link {{ $activeMenu == 'supplier' ? 'active' : '' }}">
                    <i class="nav-icon far fa-user text-white"></i>
                    <p class="text-white">Data Supplier</p>
                </a>
            </li>

            {{-- Data Transaksi --}}
            <li class="nav-header text-white">Data Transaksi</li>
            <li class="nav-item">
                <a href="{{ url('/stok') }}" class="nav-link {{ $activeMenu == 'stok' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-cubes text-white"></i>
                    <p class="text-white">Stok Barang</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/penjualan') }}" class="nav-link {{ $activeMenu == 'penjualan' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-cash-register text-white"></i>
                    <p class="text-white">Data Penjualan</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/penjualan_detail') }}" class="nav-link {{ $activeMenu == 'penjualan_detail' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-money-bill-wave text-white"></i>
                    <p class="text-white">Transaksi Penjualan</p>
                </a>
            </li>

            {{-- Lainnya --}}
            <li class="nav-header text-white">Lainnya</li>
            <li class="nav-item">
                <a href="{{ route('logout.confirm') }}" class="nav-link text-danger">
                    <i class="nav-icon fas fa-sign-out-alt text-danger"></i>
                    <p class="text-danger">Logout</p>
                </a>
            </li>
        </ul>
    </nav>


