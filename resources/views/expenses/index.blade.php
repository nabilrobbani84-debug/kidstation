@extends('layouts.app')

@section('title', 'Pencatatan Pengeluaran')

@section('content')

<!-- Form Tambah Pengeluaran -->
<div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-8">
    <div class="flex items-center gap-2 mb-6">
        <div class="w-6 h-6 rounded-full bg-red-100 flex items-center justify-center text-red-600">
            <i class="fa-solid fa-plus text-xs"></i>
        </div>
        <h3 class="font-bold text-gray-800">Tambah Pengeluaran Baru</h3>
    </div>

    <form action="{{ route('expenses.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-wide">Tanggal</label>
                <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full border border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-red-500 transition-shadow">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-wide">Kategori</label>
                <select name="category" class="w-full border border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-red-500 transition-shadow bg-white">
                    <option value="">Pilih Kategori</option>
                    <option value="Stok Barang">Stok Barang</option>
                    <option value="Operasional">Operasional</option>
                    <option value="Gaji Karyawan">Gaji Karyawan</option>
                    <option value="Sewa Tempat">Sewa Tempat</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-wide">Keterangan</label>
                <input type="text" name="description" class="w-full border border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-red-500 transition-shadow" placeholder="Deskripsi pengeluaran">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-wide">Jumlah (Rp)</label>
                <input type="number" name="amount" class="w-full border border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-red-500 transition-shadow" placeholder="0">
            </div>
        </div>
        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg shadow-lg shadow-red-200 transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
            <i class="fa-solid fa-save"></i>
            Simpan Pengeluaran
        </button>
    </form>
</div>

<!-- Daftar Pengeluaran -->
<div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center gap-2">
            <div class="w-6 h-6 rounded-full bg-red-100 flex items-center justify-center text-red-600">
                <i class="fa-solid fa-list text-xs"></i>
            </div>
            <h3 class="font-bold text-gray-800">Daftar Pengeluaran</h3>
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
                    <th class="pb-4 font-bold">Kategori</th>
                    <th class="pb-4 font-bold">Keterangan</th>
                    <th class="pb-4 font-bold text-right">Jumlah</th>
                    <th class="pb-4 font-bold text-center w-24">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm">
                @forelse($expenses as $index => $expense)
                <tr class="border-b border-gray-50 last:border-0 hover:bg-gray-50 transition-colors">
                    <td class="py-4 text-center">{{ $index + 1 }}</td>
                    <td class="py-4">{{ \Carbon\Carbon::parse($expense->date)->format('d/m/Y') }}</td>
                    <td class="py-4">
                        <span class="bg-red-50 text-red-600 text-xs px-2 py-1 rounded font-medium">{{ $expense->category }}</span>
                    </td>
                    <td class="py-4 text-gray-800">{{ $expense->description }}</td>
                    <td class="py-4 text-right font-bold text-red-600">Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                    <td class="py-4 text-center">
                        <button class="text-gray-400 hover:text-red-500 transition-colors"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-10 text-center text-gray-400">
                        Belum ada data pengeluaran
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
