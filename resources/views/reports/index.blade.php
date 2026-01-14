@extends('layouts.app')

@section('title', 'Rekap Laporan')

@section('content')

<!-- Filter Laporan -->
<div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-8">
    <div class="flex items-center gap-2 mb-6">
        <div class="w-6 h-6 rounded-full bg-green-100 flex items-center justify-center text-green-600">
            <i class="fa-solid fa-filter text-xs"></i>
        </div>
        <h3 class="font-bold text-gray-800">Filter Laporan</h3>
    </div>

    <form action="{{ route('reports.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
        <div class="w-full md:w-1/4">
            <label class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-wide">Periode</label>
            <select name="period" class="w-full border border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 transition-shadow bg-white">
                <option value="daily">Harian</option>
                <option value="monthly">Bulanan</option>
            </select>
        </div>
        <div class="w-full md:w-1/4">
            <label class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-wide">Tanggal</label>
            <input type="date" name="date" value="{{ $date }}" class="w-full border border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 transition-shadow">
        </div>
        <div class="w-full md:w-auto">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg shadow-lg shadow-green-200 transition-all transform hover:-translate-y-0.5 flex items-center gap-2 w-full md:w-auto justify-center">
                <i class="fa-solid fa-search"></i>
                Tampilkan Laporan
            </button>
        </div>
    </form>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-green-500">
        <p class="text-gray-500 text-sm font-medium mb-1">Total Penjualan</p>
        <h3 class="text-2xl font-bold text-green-600">Rp {{ number_format($totalSales, 0, ',', '.') }}</h3>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-red-500">
        <p class="text-gray-500 text-sm font-medium mb-1">Total Pengeluaran</p>
        <h3 class="text-2xl font-bold text-red-600">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</h3>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Detail Penjualan -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-arrow-up text-green-500"></i>
                <h3 class="font-bold text-gray-800">Detail Penjualan</h3>
            </div>
            <a href="{{ route('reports.export.sales', ['date' => $date]) }}" class="text-blue-600 font-bold text-sm hover:underline"><i class="fa-solid fa-download mr-1"></i> Export</a>
        </div>
        
        <table class="w-full text-left">
            <thead>
                <tr class="text-gray-400 text-xs uppercase tracking-wider border-b border-gray-100">
                    <th class="pb-2 font-bold">Tanggal</th>
                    <th class="pb-2 font-bold">Produk</th>
                    <th class="pb-2 font-bold text-center">Qty</th>
                    <th class="pb-2 font-bold text-right">Total</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm">
                @forelse($sales as $sale)
                <tr class="border-b border-gray-50 last:border-0 hover:bg-gray-50">
                    <td class="py-3">{{ \Carbon\Carbon::parse($sale->date)->format('d/m') }}</td>
                    <td class="py-3 font-medium">{{ $sale->product->name }}</td>
                    <td class="py-3 text-center">{{ $sale->quantity }}</td>
                    <td class="py-3 text-right">Rp {{ number_format($sale->total_price, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-6 text-center text-gray-400">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Detail Pengeluaran -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-arrow-down text-red-500"></i>
                <h3 class="font-bold text-gray-800">Detail Pengeluaran</h3>
            </div>
            <a href="{{ route('reports.export.expenses', ['date' => $date]) }}" class="text-blue-600 font-bold text-sm hover:underline"><i class="fa-solid fa-download mr-1"></i> Export</a>
        </div>

        <table class="w-full text-left">
            <thead>
                <tr class="text-gray-400 text-xs uppercase tracking-wider border-b border-gray-100">
                    <th class="pb-2 font-bold">Tanggal</th>
                    <th class="pb-2 font-bold">Kategori</th>
                    <th class="pb-2 font-bold">Keterangan</th>
                    <th class="pb-2 font-bold text-right">Jumlah</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm">
                @forelse($expenses as $expense)
                <tr class="border-b border-gray-50 last:border-0 hover:bg-gray-50">
                    <td class="py-3">{{ \Carbon\Carbon::parse($expense->date)->format('d/m') }}</td>
                    <td class="py-3">
                        <span class="text-xs font-bold text-red-500 bg-red-50 px-2 py-1 rounded">{{ $expense->category }}</span>
                    </td>
                    <td class="py-3">{{ Str::limit($expense->description, 20) }}</td>
                    <td class="py-3 text-right">Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-6 text-center text-gray-400">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
