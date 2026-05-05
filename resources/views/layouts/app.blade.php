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
        [x-cloak] { display: none !important; }
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
<body class="bg-gray-50 text-gray-800 overflow-x-hidden">
    <div x-data="{ sidebarOpen: false }" class="min-h-screen lg:flex lg:overflow-hidden">
        <div
            x-cloak
            x-show="sidebarOpen"
            x-transition.opacity
            @click="sidebarOpen = false"
            class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm lg:hidden"
        ></div>

        <!-- Sidebar -->
        <aside
            class="fixed inset-y-0 left-0 z-50 flex w-72 max-w-[85vw] -translate-x-full flex-col sidebar-gradient text-white shadow-2xl transition-transform duration-300 lg:static lg:z-20 lg:h-screen lg:max-w-none lg:translate-x-0"
            :class="sidebarOpen ? '!translate-x-0' : '-translate-x-full'"
        >
            <div class="p-6 sm:p-8 flex items-center gap-4">
                <div class="bg-white/20 p-3 rounded-2xl backdrop-blur-sm shadow-inner">
                    <i class="fa-solid fa-shapes text-2xl text-white"></i>
                </div>
                <div>
                    <h1 class="font-bold text-2xl tracking-tight">Kidstation</h1>
                    <p class="text-xs text-indigo-200 font-medium tracking-wide">Premium Baby Shop</p>
                </div>
                <button @click="sidebarOpen = false" class="ml-auto flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-white transition hover:bg-white/20 lg:hidden" aria-label="Tutup menu">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <nav class="flex-1 px-6 mt-4 space-y-2">
                <p class="px-4 text-xs font-semibold text-indigo-200/60 uppercase tracking-wider mb-2">Menu Utama</p>
                
                <a href="{{ route('dashboard') }}" @click="sidebarOpen = false" class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('dashboard') ? 'bg-white/20 shadow-lg text-white font-semibold' : 'hover:bg-white/10 text-indigo-100 hover:text-white' }}">
                    <i class="fa-solid fa-chart-pie w-6 text-center text-lg {{ request()->routeIs('dashboard') ? 'text-white' : 'text-indigo-300 group-hover:text-white' }}"></i>
                    <span class="text-sm">Dashboard</span>
                    @if(request()->routeIs('dashboard'))
                        <i class="fa-solid fa-chevron-right ml-auto text-xs opacity-70"></i>
                    @endif
                </a>

                <a href="{{ route('sales.index') }}" @click="sidebarOpen = false" class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('sales.*') ? 'bg-white/20 shadow-lg text-white font-semibold' : 'hover:bg-white/10 text-indigo-100 hover:text-white' }}">
                    <i class="fa-solid fa-cart-shopping w-6 text-center text-lg {{ request()->routeIs('sales.*') ? 'text-white' : 'text-indigo-300 group-hover:text-white' }}"></i>
                    <span class="text-sm">Penjualan</span>
                    @if(request()->routeIs('sales.*'))
                        <i class="fa-solid fa-chevron-right ml-auto text-xs opacity-70"></i>
                    @endif
                </a>

                <a href="{{ route('expenses.index') }}" @click="sidebarOpen = false" class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('expenses.*') ? 'bg-white/20 shadow-lg text-white font-semibold' : 'hover:bg-white/10 text-indigo-100 hover:text-white' }}">
                    <i class="fa-solid fa-money-bill-wave w-6 text-center text-lg {{ request()->routeIs('expenses.*') ? 'text-white' : 'text-indigo-300 group-hover:text-white' }}"></i>
                    <span class="text-sm">Pengeluaran</span>
                    @if(request()->routeIs('expenses.*'))
                        <i class="fa-solid fa-chevron-right ml-auto text-xs opacity-70"></i>
                    @endif
                </a>

                <a href="{{ route('products.index') }}" @click="sidebarOpen = false" class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('products.*') ? 'bg-white/20 shadow-lg text-white font-semibold' : 'hover:bg-white/10 text-indigo-100 hover:text-white' }}">
                    <i class="fa-solid fa-box-open w-6 text-center text-lg {{ request()->routeIs('products.*') ? 'text-white' : 'text-indigo-300 group-hover:text-white' }}"></i>
                    <span class="text-sm">Data Produk</span>
                    @if(request()->routeIs('products.*'))
                        <i class="fa-solid fa-chevron-right ml-auto text-xs opacity-70"></i>
                    @endif
                </a>

                <a href="{{ route('reports.index') }}" @click="sidebarOpen = false" class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('reports.*') ? 'bg-white/20 shadow-lg text-white font-semibold' : 'hover:bg-white/10 text-indigo-100 hover:text-white' }}">
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
        <main class="min-w-0 flex-1 overflow-y-auto bg-gray-50/50 relative lg:h-screen">
             <!-- Background decoration -->
             <div class="absolute top-0 left-0 w-full h-64 bg-gradient-to-b from-indigo-50 to-transparent -z-10"></div>

            <div class="glass-nav sticky top-0 z-30 flex flex-col gap-4 px-4 py-4 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-8 lg:py-5">
                <div class="flex items-start gap-3">
                    <button @click="sidebarOpen = true" class="mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-white text-gray-600 shadow-sm ring-1 ring-gray-200 transition hover:text-indigo-600 hover:shadow-md lg:hidden" aria-label="Buka menu">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <div class="min-w-0">
                     <h2 class="text-xl sm:text-2xl font-bold text-gray-800 tracking-tight">@yield('title')</h2>
                     <p class="text-gray-500 text-sm mt-1 font-medium flex items-center gap-2">
                        <i class="far fa-calendar-alt text-indigo-500"></i>
                        {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                    </p>
                    </div>
                </div>
                
                <div class="flex w-full items-center gap-3 sm:w-auto">
                    <form action="{{ url()->current() }}" method="GET" class="group relative flex-1 sm:w-60 lg:w-64 xl:w-72">
                        @if(request()->filled('date'))
                            <input type="hidden" name="date" value="{{ request('date') }}">
                        @endif
                        @if(request()->filled('period'))
                            <input type="hidden" name="period" value="{{ request('period') }}">
                        @endif
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari data..." class="w-full pl-11 pr-4 py-2.5 rounded-full border border-gray-200/80 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 bg-white/80 backdrop-blur shadow-sm text-sm">
                        <button type="submit" class="absolute left-4 top-3 text-gray-400 transition-colors group-focus-within:text-indigo-500" aria-label="Cari data">
                            <i class="fa-solid fa-search"></i>
                        </button>
                    </form>

                    @php
                        $notifications = $notifications ?? collect();
                        $unreadNotificationsCount = $unreadNotificationsCount ?? 0;
                    @endphp
                    <div x-data="{ notificationOpen: false }" @click.outside="notificationOpen = false" @keydown.escape.window="notificationOpen = false" class="relative shrink-0">
                        <button
                            type="button"
                            @click="notificationOpen = !notificationOpen"
                            class="w-10 h-10 shrink-0 bg-white rounded-full shadow-sm border border-gray-200 flex items-center justify-center text-gray-500 hover:text-indigo-600 hover:shadow-md transition-all relative"
                            :aria-expanded="notificationOpen.toString()"
                            aria-label="Buka notifikasi"
                        >
                            <i class="fa-solid fa-bell"></i>
                            @if($unreadNotificationsCount > 0)
                                <span class="absolute -top-1 -right-1 min-w-5 h-5 px-1 bg-red-500 rounded-full ring-2 ring-white text-[10px] font-bold leading-5 text-white text-center">
                                    {{ $unreadNotificationsCount > 9 ? '9+' : $unreadNotificationsCount }}
                                </span>
                            @endif
                        </button>

                        <div
                            x-cloak
                            x-show="notificationOpen"
                            x-transition.origin.top.right
                            class="absolute right-0 top-12 z-50 w-[calc(100vw-2rem)] overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-2xl shadow-slate-900/15 sm:w-96"
                        >
                            <div class="flex items-center justify-between gap-3 border-b border-gray-100 px-5 py-4">
                                <div>
                                    <h3 class="font-bold text-gray-900">Notifikasi</h3>
                                    <p class="text-xs text-gray-500">
                                        {{ $unreadNotificationsCount > 0 ? $unreadNotificationsCount.' belum dibaca' : 'Semua sudah dibaca' }}
                                    </p>
                                </div>

                                @if($unreadNotificationsCount > 0)
                                    <form action="{{ route('notifications.read') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="rounded-full bg-indigo-50 px-3 py-1.5 text-xs font-bold text-indigo-600 transition hover:bg-indigo-100">
                                            Tandai dibaca
                                        </button>
                                    </form>
                                @endif
                            </div>

                            <div class="max-h-96 overflow-y-auto p-2">
                                @forelse($notifications as $notification)
                                    @php
                                        $toneClass = match ($notification['tone']) {
                                            'emerald' => 'bg-emerald-50 text-emerald-600 ring-emerald-100',
                                            'rose' => 'bg-rose-50 text-rose-600 ring-rose-100',
                                            'amber' => 'bg-amber-50 text-amber-600 ring-amber-100',
                                            default => 'bg-indigo-50 text-indigo-600 ring-indigo-100',
                                        };
                                    @endphp
                                    <a href="{{ $notification['url'] }}" @click="notificationOpen = false" class="flex gap-3 rounded-2xl px-3 py-3 transition hover:bg-gray-50">
                                        <span class="mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl ring-1 {{ $toneClass }}">
                                            <i class="fa-solid {{ $notification['icon'] }}"></i>
                                        </span>
                                        <span class="min-w-0 flex-1">
                                            <span class="flex items-start justify-between gap-2">
                                                <span class="font-bold text-sm text-gray-900">{{ $notification['title'] }}</span>
                                                @if($notification['unread'])
                                                    <span class="mt-1.5 h-2 w-2 shrink-0 rounded-full bg-red-500"></span>
                                                @endif
                                            </span>
                                            <span class="mt-1 block text-sm leading-5 text-gray-600">{{ $notification['body'] }}</span>
                                            <span class="mt-2 block text-xs font-medium text-gray-400">{{ $notification['time'] }}</span>
                                        </span>
                                    </a>
                                @empty
                                    <div class="px-5 py-10 text-center">
                                        <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-gray-50 text-gray-400">
                                            <i class="fa-regular fa-bell"></i>
                                        </div>
                                        <p class="font-bold text-gray-800">Belum ada notifikasi</p>
                                        <p class="mt-1 text-sm text-gray-500">Notifikasi akan muncul setelah ada aktivitas toko.</p>
                                    </div>
                                @endforelse
                            </div>

                            <div class="border-t border-gray-100 bg-gray-50 px-5 py-3">
                                <a href="{{ route('reports.index') }}" class="flex items-center justify-center gap-2 text-sm font-bold text-indigo-600 hover:text-indigo-700">
                                    Lihat laporan
                                    <i class="fa-solid fa-arrow-right text-xs"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-4 sm:p-6 lg:p-8">
                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 sm:px-6 py-4 rounded-xl shadow-sm mb-6 flex items-start sm:items-center justify-between gap-4 animate-fade-in-down" role="alert">
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
