<aside class="fixed left-4 top-4 bottom-4 w-64
             bg-gray-300/40 backdrop-blur-2xl
             rounded-3xl shadow-xl shadow-black/10
             border border-white/30
             p-6">

    {{-- Logo --}}
    <div class="flex flex-col items-center justify-center gap-3 mb-8 px-2">
        <img src="{{ asset('logo1.png') }}"
             alt="Hay Kafe"
             class="w-20 h-auto rounded-lg object-contain mr-4">
        <p class="text-sm text-gray-700 tracking-wide mr-4">
            Cafe & Space
        </p>
    </div>
    
    <nav class="space-y-1">

        {{-- Dashboard --}}
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition
           {{ request()->routeIs('admin.dashboard')
                ? 'bg-white text-blue-700 shadow'
                : 'text-gray-700 hover:bg-white/70' }}">
            <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
            <span>Dashboard</span>
        </a>

        {{-- Produk (judul section) --}}
        <p class="text-xs uppercase text-gray-500 mt-6 mb-2 px-4">
            Menu Produk
        </p>

        <a href="{{ route('admin.kategori') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition
           {{ request()->routeIs('admin.kategori*')
                ? 'bg-white text-blue-700 shadow'
                : 'text-gray-700 hover:bg-white/70' }}">
            <i data-lucide="tags" class="w-5 h-5"></i>
            <span>Kategori</span>
        </a>

        <a href="{{ route('admin.produk') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition
           {{ request()->routeIs('admin.produk*')
                ? 'bg-white text-blue-700 shadow'
                : 'text-gray-700 hover:bg-white/70' }}">
            <i data-lucide="package" class="w-5 h-5"></i>
            <span>Produk</span>
        </a>

        <a href="{{ route('admin.penjualan') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition
           {{ request()->routeIs('admin.penjualan*')
                ? 'bg-white text-blue-700 shadow'
                : 'text-gray-700 hover:bg-white/70' }}">
            <i data-lucide="shopping-cart" class="w-5 h-5"></i>
            <span>Penjualan</span>
        </a>

        {{-- Finansial --}}
        <p class="text-xs uppercase text-gray-500 mt-6 mb-2 px-4">
            Finansial & SDM
        </p>

        <a href="{{ route('admin.pengeluaran') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition
           {{ request()->routeIs('admin.pengeluaran*')
                ? 'bg-white text-blue-700 shadow'
                : 'text-gray-700 hover:bg-white/70' }}">
            <i data-lucide="wallet" class="w-5 h-5"></i>
            <span>Pengeluaran</span>
        </a>

        <a href="{{ route('admin.pemasukan') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition
           {{ request()->routeIs('admin.pemasukan*')
                ? 'bg-white text-blue-700 shadow'
                : 'text-gray-700 hover:bg-white/70' }}">
            <i data-lucide="trending-up" class="w-5 h-5"></i>
            <span>Pemasukan</span>
        </a>

        <a href="{{ route('admin.kasir') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition
           {{ request()->routeIs('admin.kasir*')
                ? 'bg-white text-blue-700 shadow'
                : 'text-gray-700 hover:bg-white/70' }}">
            <i data-lucide="users" class="w-5 h-5"></i>
            <span>Manage Kasir</span>
        </a>

    </nav>
</aside>
