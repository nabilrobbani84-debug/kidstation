@extends('layouts.app')

@section('title', 'Pencatatan Penjualan')

@section('content')

@if(isset($dbError) && $dbError)
    <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 p-4 flex gap-4 items-start text-rose-800 shadow-sm">
        <i class="fa-solid fa-triangle-exclamation mt-1 text-xl text-rose-500"></i>
        <div>
            <h3 class="font-bold">Koneksi Database Gagal</h3>
            <p class="text-sm mt-1">{{ $dbError }}</p>
        </div>
    </div>
@endif

@error('db')
    <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800">
        <i class="fa-solid fa-circle-xmark mr-2"></i>{{ $message }}
    </div>
@enderror

<!-- Form Tambah Penjualan -->
<div class="bg-white p-4 sm:p-6 rounded-2xl shadow-sm border border-gray-100 mb-8">
    <div class="flex items-center gap-2 mb-6">
        <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
            <i class="fa-solid fa-plus text-xs"></i>
        </div>
        <h3 class="font-bold text-gray-800">Tambah Penjualan Baru</h3>
    </div>

    <form action="{{ route('sales.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-wide">Tanggal</label>
                <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full border border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-shadow">
            </div>
            <div class="md:col-span-1">
                <label class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-wide">Produk</label>
                <select name="product_id" id="productSelect" class="w-full border border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-shadow bg-white">
                    <option value="" data-price="0">Pilih Produk</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" data-price="{{ $product->price }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-wide">Jumlah</label>
                <input type="number" name="quantity" id="quantityInput" class="w-full border border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-shadow" placeholder="Qty">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-wide">Total (Auto)</label>
                <input type="text" id="totalDisplay" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-4 py-3 focus:outline-none text-gray-500 cursor-not-allowed" placeholder="Rp 0" readonly>
            </div>
        </div>
        <button type="submit" class="w-full sm:w-auto justify-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg shadow-lg shadow-blue-200 transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
            <i class="fa-solid fa-save"></i>
            Simpan Penjualan
        </button>
    </form>
</div>

<!-- Daftar Penjualan -->
<div class="bg-white p-4 sm:p-6 rounded-2xl shadow-sm border border-gray-100">
    <div class="flex flex-col gap-4 mb-6 lg:flex-row lg:justify-between lg:items-center">
        <div class="flex items-center gap-2">
            <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                <i class="fa-solid fa-list text-xs"></i>
            </div>
            <h3 class="font-bold text-gray-800">Daftar Penjualan</h3>
        </div>
        <form action="{{ route('sales.index') }}" method="GET" class="grid w-full grid-cols-1 gap-2 sm:grid-cols-[1fr_180px_auto_auto] lg:w-auto">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari produk..." class="rounded-lg border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <input type="date" name="date" value="{{ request('date') }}" class="rounded-lg border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit" class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-bold text-white transition hover:bg-blue-700">Filter</button>
            @if(request()->filled('q') || request()->filled('date'))
                <a href="{{ route('sales.index') }}" class="rounded-lg border border-gray-200 px-5 py-2.5 text-center text-sm font-bold text-gray-500 transition hover:bg-gray-50">Reset</a>
            @endif
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-[720px] w-full text-left">
            <thead>
                <tr class="text-gray-400 text-xs uppercase tracking-wider border-b border-gray-100">
                    <th class="pb-4 font-bold text-center w-16">No</th>
                    <th class="pb-4 font-bold">Tanggal</th>
                    <th class="pb-4 font-bold">Produk</th>
                    <th class="pb-4 font-bold text-center">Jumlah</th>
                    <th class="pb-4 font-bold text-right">Total</th>
                    <th class="pb-4 font-bold text-center w-24">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm">
                @forelse($sales as $index => $sale)
                <tr class="border-b border-gray-50 last:border-0 hover:bg-gray-50 transition-colors">
                    <td class="py-4 text-center">{{ $index + 1 }}</td>
                    <td class="py-4">{{ \Carbon\Carbon::parse($sale->date)->format('d/m/Y') }}</td>
                    <td class="py-4 font-medium text-gray-800">{{ $sale->product?->name ?? 'Produk Terhapus' }}</td>
                    <td class="py-4 text-center">{{ $sale->quantity }}</td>
                    <td class="py-4 text-right font-bold text-blue-600">Rp {{ number_format($sale->total_price, 0, ',', '.') }}</td>
                    <td class="py-4 text-center">
                        <form action="{{ route('sales.destroy', $sale) }}" method="POST" onsubmit="return confirm('Hapus data penjualan ini?')" class="inline-flex">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-10 text-center text-gray-400">
                        {{ request()->filled('q') || request()->filled('date') ? 'Data penjualan tidak ditemukan' : 'Belum ada data penjualan' }}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    const productSelect = document.getElementById('productSelect');
    const quantityInput = document.getElementById('quantityInput');
    const totalDisplay = document.getElementById('totalDisplay');

    function calculateTotal() {
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
        const quantity = parseInt(quantityInput.value) || 0;
        const total = price * quantity;
        
        totalDisplay.value = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
    }

    productSelect.addEventListener('change', calculateTotal);
    quantityInput.addEventListener('input', calculateTotal);
</script>

@endsection
