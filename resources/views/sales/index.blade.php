@extends('layouts.app')

@section('title', 'Pencatatan Penjualan')

@section('content')

<!-- Form Tambah Penjualan -->
<div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-8">
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
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg shadow-lg shadow-blue-200 transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
            <i class="fa-solid fa-save"></i>
            Simpan Penjualan
        </button>
    </form>
</div>

<!-- Daftar Penjualan -->
<div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center gap-2">
            <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                <i class="fa-solid fa-list text-xs"></i>
            </div>
            <h3 class="font-bold text-gray-800">Daftar Penjualan</h3>
        </div>
        <div class="flex items-center gap-2 border border-gray-200 rounded-lg px-3 py-1.5 cursor-pointer hover:bg-gray-50">
            <span class="text-sm text-gray-600">hh/bb/tttt</span>
            <i class="fa-regular fa-calendar text-gray-400"></i>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
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
                    <td class="py-4 font-medium text-gray-800">{{ $sale->product->name }}</td>
                    <td class="py-4 text-center">{{ $sale->quantity }}</td>
                    <td class="py-4 text-right font-bold text-blue-600">Rp {{ number_format($sale->total_price, 0, ',', '.') }}</td>
                    <td class="py-4 text-center">
                        <button class="text-gray-400 hover:text-red-500 transition-colors"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-10 text-center text-gray-400">
                        Belum ada data penjualan
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
