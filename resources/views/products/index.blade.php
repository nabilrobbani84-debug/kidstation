@extends('layouts.app')

@section('title', 'Manajemen Produk')

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

<!-- Form Tambah Produk -->
<div class="bg-white p-4 sm:p-6 rounded-2xl shadow-sm border border-gray-100 mb-8">
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
        <button type="submit" class="w-full sm:w-auto justify-center bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-6 rounded-lg shadow-lg shadow-purple-200 transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
            <i class="fa-solid fa-save"></i>
            Simpan Produk
        </button>
    </form>
</div>

<!-- Daftar Produk -->
<div class="bg-white p-4 sm:p-6 rounded-2xl shadow-sm border border-gray-100">
    <div class="flex flex-col gap-4 mb-6 lg:flex-row lg:items-center lg:justify-between">
        <div class="flex items-center gap-2">
            <div class="w-6 h-6 rounded-full bg-purple-100 flex items-center justify-center text-purple-600">
                <i class="fa-solid fa-box text-xs"></i>
            </div>
            <h3 class="font-bold text-gray-800">Daftar Produk</h3>
        </div>
        <form action="{{ route('products.index') }}" method="GET" class="flex w-full flex-col gap-2 sm:flex-row lg:w-auto">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari produk/kategori..." class="w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 lg:w-64">
            <button type="submit" class="rounded-lg bg-purple-600 px-5 py-2.5 text-sm font-bold text-white transition hover:bg-purple-700">Cari</button>
            @if(request()->filled('q'))
                <a href="{{ route('products.index') }}" class="rounded-lg border border-gray-200 px-5 py-2.5 text-center text-sm font-bold text-gray-500 transition hover:bg-gray-50">Reset</a>
            @endif
        </form>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($products as $product)
        <div x-data="{ editing: false }" class="border border-gray-100 rounded-xl p-5 hover:shadow-md transition-shadow relative group">
            <div class="flex justify-between items-start mb-4">
                <span class="bg-purple-50 text-purple-600 text-xs font-bold px-2 py-1 rounded">{{ $product->category }}</span>
                <div class="flex gap-2 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                    <button type="button" @click="editing = !editing" class="text-blue-500 hover:bg-blue-50 p-1 rounded" aria-label="Edit produk"><i class="fa-solid fa-pen"></i></button>
                    <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:bg-red-50 p-1 rounded"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </div>
            </div>
            <h4 class="font-bold text-gray-800 text-lg mb-1">{{ $product->name }}</h4>
            <p class="text-purple-600 font-bold text-xl">Rp {{ number_format($product->price, 0, ',', '.') }}</p>

            <form x-cloak x-show="editing" x-transition action="{{ route('products.update', $product) }}" method="POST" class="mt-5 space-y-3 rounded-2xl border border-purple-100 bg-purple-50/50 p-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="mb-1 block text-xs font-bold uppercase tracking-wide text-gray-500">Nama Produk</label>
                    <input type="text" name="name" value="{{ $product->name }}" class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
                <div>
                    <label class="mb-1 block text-xs font-bold uppercase tracking-wide text-gray-500">Kategori</label>
                    <select name="category" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="Susu Pertumbuhan" @selected($product->category === 'Susu Pertumbuhan')>Susu Pertumbuhan</option>
                        <option value="Popok Bayi" @selected($product->category === 'Popok Bayi')>Popok Bayi</option>
                        <option value="Perlengkapan Bayi" @selected($product->category === 'Perlengkapan Bayi')>Perlengkapan Bayi</option>
                        <option value="Pakaian Bayi" @selected($product->category === 'Pakaian Bayi')>Pakaian Bayi</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-bold uppercase tracking-wide text-gray-500">Harga (Rp)</label>
                    <input type="number" name="price" value="{{ $product->price }}" class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 rounded-lg bg-purple-600 px-4 py-2 text-sm font-bold text-white transition hover:bg-purple-700">Simpan</button>
                    <button type="button" @click="editing = false" class="rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-bold text-gray-500 transition hover:bg-gray-50">Batal</button>
                </div>
            </form>
        </div>
        @empty
        <div class="col-span-full text-center py-10 text-gray-400">
            <p>{{ request()->filled('q') ? 'Produk tidak ditemukan' : 'Belum ada produk' }}</p>
        </div>
        @endforelse
    </div>
</div>

@endsection
