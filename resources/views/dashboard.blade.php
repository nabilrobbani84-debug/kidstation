@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
@php \Carbon\Carbon::setLocale('id'); @endphp

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-6 mb-8">
    <!-- Card 1: Penjualan -->
    <div class="bg-gradient-to-br from-emerald-400 to-teal-500 text-white p-6 rounded-3xl shadow-xl shadow-emerald-500/20 relative overflow-hidden group hover:-translate-y-1 transition-all duration-300">
        <div class="relative z-10">
            <div class="bg-white/20 w-12 h-12 rounded-2xl flex items-center justify-center mb-4 backdrop-blur-sm shadow-inner-white">
                <i class="fa-solid fa-arrow-trend-up text-white text-xl"></i>
            </div>
            <p class="text-emerald-50 text-sm font-medium mb-1 tracking-wide">Total Penjualan</p>
            <h3 class="text-2xl sm:text-3xl font-extrabold tracking-tight break-words">Rp {{ number_format($totalSales, 0, ',', '.') }}</h3>
        </div>
        <div class="absolute top-4 right-4 bg-black/10 px-3 py-1 rounded-full text-xs font-semibold backdrop-blur-sm">Hari Ini</div>
        <i class="fa-solid fa-chart-line absolute -bottom-6 -right-6 text-9xl text-white opacity-10 group-hover:scale-110 group-hover:rotate-12 transition-all duration-500"></i>
    </div>

    <!-- Card 2: Pengeluaran -->
    <div class="bg-gradient-to-br from-rose-500 to-pink-600 text-white p-6 rounded-3xl shadow-xl shadow-rose-500/20 relative overflow-hidden group hover:-translate-y-1 transition-all duration-300">
        <div class="relative z-10">
            <div class="bg-white/20 w-12 h-12 rounded-2xl flex items-center justify-center mb-4 backdrop-blur-sm shadow-inner-white">
                <i class="fa-solid fa-arrow-trend-down text-white text-xl"></i>
            </div>
            <p class="text-rose-50 text-sm font-medium mb-1 tracking-wide">Total Pengeluaran</p>
            <h3 class="text-2xl sm:text-3xl font-extrabold tracking-tight break-words">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</h3>
        </div>
        <div class="absolute top-4 right-4 bg-black/10 px-3 py-1 rounded-full text-xs font-semibold backdrop-blur-sm">Hari Ini</div>
        <i class="fa-solid fa-wallet absolute -bottom-6 -right-6 text-9xl text-white opacity-10 group-hover:scale-110 group-hover:rotate-12 transition-all duration-500"></i>
    </div>

    

    <!-- Card 4: Transaksi -->
    <div class="bg-gradient-to-br from-violet-500 to-purple-600 text-white p-6 rounded-3xl shadow-xl shadow-violet-500/20 relative overflow-hidden group hover:-translate-y-1 transition-all duration-300">
        <div class="relative z-10">
            <div class="bg-white/20 w-12 h-12 rounded-2xl flex items-center justify-center mb-4 backdrop-blur-sm shadow-inner-white">
                <i class="fa-solid fa-receipt text-white text-xl"></i>
            </div>
            <p class="text-violet-50 text-sm font-medium mb-1 tracking-wide">Jumlah Transaksi</p>
            <h3 class="text-2xl sm:text-3xl font-extrabold tracking-tight break-words">{{ number_format($transactionCount) }}</h3>
        </div>
        <div class="absolute top-4 right-4 bg-black/10 px-3 py-1 rounded-full text-xs font-semibold backdrop-blur-sm">Hari Ini</div>
        <i class="fa-solid fa-bag-shopping absolute -bottom-6 -right-6 text-9xl text-white opacity-10 group-hover:scale-110 group-hover:rotate-12 transition-all duration-500"></i>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
    <!-- Chart Section -->
    <div class="lg:col-span-2 bg-white p-4 sm:p-6 rounded-2xl shadow-sm border border-gray-100 min-w-0">
        <div class="flex flex-col gap-3 sm:flex-row sm:justify-between sm:items-center mb-6">
            <h3 class="font-bold text-gray-800 text-lg">Grafik Penjualan & Pengeluaran</h3>
            <form action="{{ route('dashboard') }}" method="GET" class="w-full sm:w-auto">
                @if(request()->filled('q'))
                    <input type="hidden" name="q" value="{{ request('q') }}">
                @endif
                <select name="chart_range" onchange="this.form.submit()" class="w-full sm:w-auto border border-gray-300 rounded-lg text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="7" @selected($chartRange === 7)>7 Hari Terakhir</option>
                    <option value="30" @selected($chartRange === 30)>30 Hari Terakhir</option>
                </select>
            </form>
        </div>
        <div class="relative h-72" data-chart-container>
            <canvas id="salesChart"></canvas>
            <div id="chartFallback" class="hidden absolute inset-0 items-center justify-center rounded-2xl bg-gray-50 text-center">
                <div>
                    <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-white text-gray-400 shadow-sm">
                        <i class="fa-solid fa-chart-line"></i>
                    </div>
                    <p class="font-bold text-gray-700">Grafik belum bisa dimuat</p>
                    <p class="mt-1 text-sm text-gray-500">Refresh halaman atau cek koneksi internet untuk Chart.js.</p>
                </div>
            </div>
        </div>
        @unless($hasChartData)
            <p class="mt-4 rounded-xl bg-amber-50 px-4 py-3 text-sm font-medium text-amber-700">
                Belum ada data penjualan atau pengeluaran untuk {{ $chartRange }} hari terakhir.
            </p>
        @endunless
    </div>

    <!-- Recent Transactions -->
    <div class="bg-white p-4 sm:p-6 rounded-2xl shadow-sm border border-gray-100 min-w-0">
        <h3 class="font-bold text-gray-800 text-lg mb-6">Transaksi Terbaru</h3>
        
        <div class="space-y-4">
            @forelse($recentTransactions as $transaction)
            <div class="flex flex-col gap-3 p-3 hover:bg-gray-50 rounded-lg transition-colors border-b border-gray-50 last:border-0 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex min-w-0 items-center gap-3">
                    <div class="w-10 h-10 shrink-0 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                        <i class="fa-solid fa-basket-shopping"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="font-bold text-gray-800 text-sm truncate">{{ $transaction->product ? $transaction->product->name : 'Produk Terhapus' }}</p>
                        <p class="text-xs text-gray-500">{{ $transaction->created_at->format('H:i') }} • Qty: {{ $transaction->quantity }}</p>
                    </div>
                </div>
                <div class="text-left sm:text-right">
                    <p class="font-bold text-green-600 text-sm">+Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-400">{{ $transaction->date }}</p>
                </div>
            </div>
            @empty
            <div class="text-center py-10 text-gray-400">
                <p>Belum ada transaksi</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const canvas = document.getElementById('salesChart');
        const fallback = document.getElementById('chartFallback');

        if (!canvas || !window.Chart) {
            fallback?.classList.remove('hidden');
            fallback?.classList.add('flex');
            return;
        }

        const rupiah = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0,
        });

        new Chart(canvas, {
            type: 'line',
            data: {
                labels: @json($dates),
                datasets: [{
                    label: 'Penjualan',
                    data: @json($chartData['sales']),
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.12)',
                    pointBackgroundColor: '#10b981',
                    pointBorderColor: '#ffffff',
                    pointHoverRadius: 6,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }, {
                    label: 'Pengeluaran',
                    data: @json($chartData['expenses']),
                    borderColor: '#f43f5e',
                    backgroundColor: 'rgba(244, 63, 94, 0.10)',
                    pointBackgroundColor: '#f43f5e',
                    pointBorderColor: '#ffffff',
                    pointHoverRadius: 6,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            boxWidth: 10,
                            usePointStyle: true,
                        },
                    },
                    tooltip: {
                        callbacks: {
                            label: (context) => `${context.dataset.label}: ${rupiah.format(context.parsed.y || 0)}`,
                        },
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: (value) => rupiah.format(value),
                        },
                        grid: {
                            color: 'rgba(15, 23, 42, 0.06)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    });
</script>

@endsection
