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
        $sales = Sale::with('product')
            ->when($request->filled('date'), fn ($query) => $query->whereDate('date', $request->input('date')))
            ->when($request->filled('q'), function ($query) use ($request) {
                $keyword = $request->input('q');

                $query->whereHas('product', fn ($query) => $query->where('name', 'like', "%{$keyword}%"));
            })
            ->latest()
            ->get();

        $products = Product::orderBy('name')->get();

        return view('sales.index', compact('sales', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::find($request->product_id);
        $total_price = $product->price * $request->quantity;

        Sale::create([
            'date' => $request->date,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'total_price' => $total_price,
        ]);

        return redirect()->route('sales.index')->with('success', 'Penjualan berhasil disimpan');
    }

    public function destroy(Sale $sale)
    {
        $sale->delete();

        return redirect()->route('sales.index')->with('success', 'Penjualan berhasil dihapus');
    }
}
