<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products   = collect();
        $categories = collect();
        $dbError    = null;

        try {
            $products = Product::query()
                ->when($request->filled('q'), function ($q) use ($request) {
                    $keyword = $request->input('q');
                    $q->where(function ($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%")
                          ->orWhere('category', 'like', "%{$keyword}%");
                    });
                })
                ->latest()
                ->get();

            // Ambil semua kategori unik dari DB + kategori default
            $dbCategories = Product::select('category')
                ->distinct()
                ->orderBy('category')
                ->pluck('category');

            $defaultCategories = collect([
                'Susu', 'Susu Pertumbuhan', 'Popok Bayi',
                'Perlengkapan Bayi', 'Pakaian Bayi', 'Makanan Bayi', 'Lainnya',
            ]);

            $categories = $defaultCategories->merge($dbCategories)->unique()->sort()->values();
        } catch (\Throwable $e) {
            $dbError = 'Tidak dapat terhubung ke database: ' . $e->getMessage();
        }

        return view('products.index', compact('products', 'categories', 'dbError'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required',
            'category' => 'required',
            'price'    => 'required|numeric',
        ]);

        try {
            Product::create($request->only('name', 'category', 'price'));
            return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan');
        } catch (\Throwable $e) {
            return back()->withErrors(['db' => 'Gagal menambahkan produk: ' . $e->getMessage()])->withInput();
        }
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'     => 'required',
            'category' => 'required',
            'price'    => 'required|numeric',
        ]);

        try {
            $product->update($validated);
            return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui');
        } catch (\Throwable $e) {
            return back()->withErrors(['db' => 'Gagal memperbarui produk: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy(Product $product)
    {
        try {
            $product->delete();
            return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus');
        } catch (\Throwable $e) {
            return back()->withErrors(['db' => 'Gagal menghapus produk: ' . $e->getMessage()]);
        }
    }
}
