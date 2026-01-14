@extends('layouts.app')

@section('title', 'Manajemen Produk')

@section('content')

<!-- Form Tambah Produk -->
<div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-8">
    <div class="flex items-center gap-2 mb-6">
        <div class="w-6 h-6 rounded-full bg-purple-100 flex items-center justify-center text-purple-600">
            <i class="fa-solid fa-plus text-xs"></i>
        </div>
        <h3 class="font-bold text-gray-800">Tambah Produk Baru</h3>
    </div>

    <form action="{{ route('products.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-wide">Nama Produk</label>
                <input type="text" name="name" class="w-full border border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-shadow" placeholder="Nama produk">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-wide">Kategori</label>
                <select name="category" class="w-full border border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-shadow bg-white">
                    <option value="">Pilih Kategori</option>
                    <option value="Susu Pertumbuhan">Susu Pertumbuhan</option>
                    <option value="Popok Bayi">Popok Bayi</option>
                    <option value="Perlengkapan Bayi">Perlengkapan Bayi</option>
                    <option value="Pakaian Bayi">Pakaian Bayi</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-wide">Harga (Rp)</label>
                <input type="number" name="price" class="w-full border border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-shadow" placeholder="0">
            </div>
        </div>
        <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-6 rounded-lg shadow-lg shadow-purple-200 transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
            <i class="fa-solid fa-save"></i>
            Simpan Produk
        </button>
    </form>
</div>

<!-- Daftar Produk -->
<div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
    <div class="flex items-center gap-2 mb-6">
        <div class="w-6 h-6 rounded-full bg-purple-100 flex items-center justify-center text-purple-600">
            <i class="fa-solid fa-box text-xs"></i>
        </div>
        <h3 class="font-bold text-gray-800">Daftar Produk</h3>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($products as $product)
        <div class="border border-gray-100 rounded-xl p-5 hover:shadow-md transition-shadow relative group">
            <div class="flex justify-between items-start mb-4">
                <span class="bg-purple-50 text-purple-600 text-xs font-bold px-2 py-1 rounded">{{ $product->category }}</span>
                <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                    <button class="text-blue-500 hover:bg-blue-50 p-1 rounded"><i class="fa-solid fa-pen"></i></button>
                    <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:bg-red-50 p-1 rounded"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </div>
            </div>
            <h4 class="font-bold text-gray-800 text-lg mb-1">{{ $product->name }}</h4>
            <p class="text-purple-600 font-bold text-xl">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
        </div>
        @empty
        <div class="col-span-full text-center py-10 text-gray-400">
            <p>Belum ada produk</p>
        </div>
        @endforelse
    </div>
</div>

@endsection
