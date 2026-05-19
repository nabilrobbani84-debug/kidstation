<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $expenses = collect();
        $dbError  = null;

        try {
            $expenses = Expense::query()
                ->when($request->filled('date'), fn ($q) => $q->whereDate('date', $request->input('date')))
                ->when($request->filled('q'), function ($q) use ($request) {
                    $keyword = $request->input('q');
                    $q->where(function ($q) use ($keyword) {
                        $q->where('category', 'like', "%{$keyword}%")
                          ->orWhere('description', 'like', "%{$keyword}%");
                    });
                })
                ->latest()
                ->get();
        } catch (\Throwable $e) {
            $dbError = 'Tidak dapat terhubung ke database: ' . $e->getMessage();
        }

        return view('expenses.index', compact('expenses', 'dbError'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date'        => 'required|date',
            'category'    => 'required',
            'description' => 'required',
            'amount'      => 'required|numeric',
        ]);

        try {
            Expense::create($request->only('date', 'category', 'description', 'amount'));
            return redirect()->route('expenses.index')->with('success', 'Pengeluaran berhasil disimpan');
        } catch (\Throwable $e) {
            return back()->withErrors(['db' => 'Gagal menyimpan pengeluaran: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy(Expense $expense)
    {
        try {
            $expense->delete();
            return redirect()->route('expenses.index')->with('success', 'Pengeluaran berhasil dihapus');
        } catch (\Throwable $e) {
            return back()->withErrors(['db' => 'Gagal menghapus pengeluaran: ' . $e->getMessage()]);
        }
    }
}
