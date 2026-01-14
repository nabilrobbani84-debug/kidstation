<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kidstation - Toko Susu & Baby Shop</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Outfit', sans-serif; }
        .glass-nav {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }
        .sidebar-gradient {
            background: linear-gradient(135deg, #4f46e5 0%, #312e81 100%);
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-72 sidebar-gradient text-white flex flex-col shadow-2xl z-20 transition-all duration-300">
            <div class="p-8 flex items-center gap-4">
                <div class="bg-white/20 p-3 rounded-2xl backdrop-blur-sm shadow-inner">
                    <i class="fa-solid fa-shapes text-2xl text-white"></i>
                </div>
                <div>
                    <h1 class="font-bold text-2xl tracking-tight">Kidstation</h1>
                    <p class="text-xs text-indigo-200 font-medium tracking-wide">Premium Baby Shop</p>
                </div>
            </div>

            <nav class="flex-1 px-6 mt-4 space-y-2">
                <p class="px-4 text-xs font-semibold text-indigo-200/60 uppercase tracking-wider mb-2">Menu Utama</p>
                
                <a href="{{ route('dashboard') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('dashboard') ? 'bg-white/20 shadow-lg text-white font-semibold' : 'hover:bg-white/10 text-indigo-100 hover:text-white' }}">
                    <i class="fa-solid fa-chart-pie w-6 text-center text-lg {{ request()->routeIs('dashboard') ? 'text-white' : 'text-indigo-300 group-hover:text-white' }}"></i>
                    <span class="text-sm">Dashboard</span>
                    @if(request()->routeIs('dashboard'))
                        <i class="fa-solid fa-chevron-right ml-auto text-xs opacity-70"></i>
                    @endif
                </a>

                <a href="{{ route('sales.index') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('sales.*') ? 'bg-white/20 shadow-lg text-white font-semibold' : 'hover:bg-white/10 text-indigo-100 hover:text-white' }}">
                    <i class="fa-solid fa-cart-shopping w-6 text-center text-lg {{ request()->routeIs('sales.*') ? 'text-white' : 'text-indigo-300 group-hover:text-white' }}"></i>
                    <span class="text-sm">Penjualan</span>
                    @if(request()->routeIs('sales.*'))
                        <i class="fa-solid fa-chevron-right ml-auto text-xs opacity-70"></i>
                    @endif
                </a>

                <a href="{{ route('expenses.index') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('expenses.*') ? 'bg-white/20 shadow-lg text-white font-semibold' : 'hover:bg-white/10 text-indigo-100 hover:text-white' }}">
                    <i class="fa-solid fa-money-bill-wave w-6 text-center text-lg {{ request()->routeIs('expenses.*') ? 'text-white' : 'text-indigo-300 group-hover:text-white' }}"></i>
                    <span class="text-sm">Pengeluaran</span>
                    @if(request()->routeIs('expenses.*'))
                        <i class="fa-solid fa-chevron-right ml-auto text-xs opacity-70"></i>
                    @endif
                </a>

                <a href="{{ route('products.index') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('products.*') ? 'bg-white/20 shadow-lg text-white font-semibold' : 'hover:bg-white/10 text-indigo-100 hover:text-white' }}">
                    <i class="fa-solid fa-box-open w-6 text-center text-lg {{ request()->routeIs('products.*') ? 'text-white' : 'text-indigo-300 group-hover:text-white' }}"></i>
                    <span class="text-sm">Data Produk</span>
                    @if(request()->routeIs('products.*'))
                        <i class="fa-solid fa-chevron-right ml-auto text-xs opacity-70"></i>
                    @endif
                </a>

                <a href="{{ route('reports.index') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('reports.*') ? 'bg-white/20 shadow-lg text-white font-semibold' : 'hover:bg-white/10 text-indigo-100 hover:text-white' }}">
                    <i class="fa-solid fa-file-invoice-dollar w-6 text-center text-lg {{ request()->routeIs('reports.*') ? 'text-white' : 'text-indigo-300 group-hover:text-white' }}"></i>
                    <span class="text-sm">Laporan</span>
                    @if(request()->routeIs('reports.*'))
                        <i class="fa-solid fa-chevron-right ml-auto text-xs opacity-70"></i>
                    @endif
                </a>
            </nav>

            <div class="p-6 mt-auto">
                <div class="flex items-center gap-4 px-4 py-4 bg-black/20 rounded-2xl backdrop-blur-md border border-white/5">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-yellow-400 to-orange-500 flex items-center justify-center text-white shadow-lg">
                        <i class="fa-solid fa-user-secret"></i>
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-indigo-200 text-xs font-medium">Logged in as</p>
                        <p class="font-bold text-sm truncate text-white">Admin</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto bg-gray-50/50 relative">
             <!-- Background decoration -->
             <div class="absolute top-0 left-0 w-full h-64 bg-gradient-to-b from-indigo-50 to-transparent -z-10"></div>

            <div class="glass-nav sticky top-0 z-30 px-8 py-5 flex justify-between items-center">
                <div>
                     <h2 class="text-2xl font-bold text-gray-800 tracking-tight">@yield('title')</h2>
                     <p class="text-gray-500 text-sm mt-1 font-medium flex items-center gap-2">
                        <i class="far fa-calendar-alt text-indigo-500"></i>
                        {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                    </p>
                </div>
                
                <div class="flex items-center gap-4">
                    <div class="relative group">
                        <input type="text" placeholder="Cari data..." class="pl-11 pr-4 py-2.5 rounded-full border border-gray-200/80 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 bg-white/80 backdrop-blur w-64 transition-all group-hover:w-72 shadow-sm text-sm">
                        <i class="fa-solid fa-search absolute left-4 top-3.5 text-gray-400 transition-colors group-focus-within:text-indigo-500"></i>
                    </div>
                    
                    <button class="w-10 h-10 bg-white rounded-full shadow-sm border border-gray-200 flex items-center justify-center text-gray-500 hover:text-indigo-600 hover:shadow-md transition-all relative">
                        <i class="fa-solid fa-bell"></i>
                        <span class="absolute top-2 right-2.5 w-2 h-2 bg-red-500 rounded-full ring-2 ring-white"></span>
                    </button>
                </div>
            </div>

            <div class="p-8">
                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-6 py-4 rounded-xl shadow-sm mb-6 flex items-center justify-between animate-fade-in-down" role="alert">
                        <div class="flex items-center gap-3">
                            <div class="bg-emerald-100 p-2 rounded-full">
                                <i class="fa-solid fa-check text-emerald-600"></i>
                            </div>
                            <div>
                                <p class="font-bold">Berhasil!</p>
                                <p class="text-sm">{{ session('success') }}</p>
                            </div>
                        </div>
                        <button @click="show = false" class="text-emerald-500 hover:text-emerald-700">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
    
    <!-- AlpineJS for interactions -->
    <script src="//unpkg.com/alpinejs" defer></script>
</body>
</html>
