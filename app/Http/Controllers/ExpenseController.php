<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::latest()->get();
        return view('expenses.index', compact('expenses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'category' => 'required',
            'description' => 'required',
            'amount' => 'required|numeric',
        ]);

        Expense::create($request->all());

        return redirect()->route('expenses.index')->with('success', 'Pengeluaran berhasil disimpan');
    }
}
