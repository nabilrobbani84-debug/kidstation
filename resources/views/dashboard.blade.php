@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
@php \Carbon\Carbon::setLocale('id'); @endphp

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Card 1: Penjualan -->
    <div class="bg-gradient-to-br from-emerald-400 to-teal-500 text-white p-6 rounded-3xl shadow-xl shadow-emerald-500/20 relative overflow-hidden group hover:-translate-y-1 transition-all duration-300">
        <div class="relative z-10">
            <div class="bg-white/20 w-12 h-12 rounded-2xl flex items-center justify-center mb-4 backdrop-blur-sm shadow-inner-white">
                <i class="fa-solid fa-arrow-trend-up text-white text-xl"></i>
            </div>
            <p class="text-emerald-50 text-sm font-medium mb-1 tracking-wide">Total Penjualan</p>
            <h3 class="text-3xl font-extrabold tracking-tight">Rp {{ number_format($totalSales, 0, ',', '.') }}</h3>
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
            <h3 class="text-3xl font-extrabold tracking-tight">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</h3>
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
            <h3 class="text-3xl font-extrabold tracking-tight">{{ number_format($transactionCount) }}</h3>
        </div>
        <div class="absolute top-4 right-4 bg-black/10 px-3 py-1 rounded-full text-xs font-semibold backdrop-blur-sm">Hari Ini</div>
        <i class="fa-solid fa-bag-shopping absolute -bottom-6 -right-6 text-9xl text-white opacity-10 group-hover:scale-110 group-hover:rotate-12 transition-all duration-500"></i>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Chart Section -->
    <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-gray-800 text-lg">Grafik Penjualan & Pengeluaran</h3>
            <select class="border border-gray-300 rounded-lg text-sm px-3 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option>7 Hari Terakhir</option>
                <option>30 Hari Terakhir</option>
            </select>
        </div>
        <div class="relative h-72">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <h3 class="font-bold text-gray-800 text-lg mb-6">Transaksi Terbaru</h3>
        
        <div class="space-y-4">
            @forelse($recentTransactions as $transaction)
            <div class="flex justify-between items-center p-3 hover:bg-gray-50 rounded-lg transition-colors border-b border-gray-50 last:border-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                        <i class="fa-solid fa-basket-shopping"></i>
                    </div>
                    <div>
                        <p class="font-bold text-gray-800 text-sm">{{ $transaction->product ? $transaction->product->name : 'Produk Terhapus' }}</p>
                        <p class="text-xs text-gray-500">{{ $transaction->created_at->format('H:i') }} • Qty: {{ $transaction->quantity }}</p>
                    </div>
                </div>
                <div class="text-right">
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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($dates) !!},
            datasets: [{
                label: 'Penjualan',
                data: {!! json_encode($chartData['sales']) !!},
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                fill: true,
                tension: 0.4
            }, {
                label: 'Pengeluaran',
                data: {!! json_encode($chartData['expenses']) !!},
                borderColor: '#f43f5e',
                backgroundColor: 'rgba(244, 63, 94, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
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
</script>

@endsection
