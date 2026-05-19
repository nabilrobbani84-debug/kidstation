<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;
use Carbon\Carbon;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $sales    = collect();
        $products = collect();
        $dbError  = null;

        try {
            $sales = Sale::with('product')
                ->when($request->filled('date'), fn ($q) => $q->whereDate('date', $request->input('date')))
                ->when($request->filled('q'), function ($q) use ($request) {
                    $keyword = $request->input('q');
                    $q->whereHas('product', fn ($q) => $q->where('name', 'like', "%{$keyword}%"));
                })
                ->latest()
                ->get();

            $products = Product::orderBy('name')->get();
        } catch (\Throwable $e) {
            $dbError = 'Tidak dapat terhubung ke database: ' . $e->getMessage();
        }

        return view('sales.index', compact('sales', 'products', 'dbError'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date'       => 'required|date',
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        try {
            $product     = Product::findOrFail($request->product_id);
            $total_price = $product->price * $request->quantity;

            Sale::create([
                'date'       => $request->date,
                'product_id' => $request->product_id,
                'quantity'   => $request->quantity,
                'total_price'=> $total_price,
            ]);

            return redirect()->route('sales.index')->with('success', 'Penjualan berhasil disimpan');
        } catch (\Throwable $e) {
            return back()->withErrors(['db' => 'Gagal menyimpan penjualan: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy(Sale $sale)
    {
        try {
            $sale->delete();
            return redirect()->route('sales.index')->with('success', 'Penjualan berhasil dihapus');
        } catch (\Throwable $e) {
            return back()->withErrors(['db' => 'Gagal menghapus penjualan: ' . $e->getMessage()]);
        }
    }
}
