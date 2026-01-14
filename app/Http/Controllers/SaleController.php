<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;
use Carbon\Carbon;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with('product')->latest()->get();
        $products = Product::all();
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
}
