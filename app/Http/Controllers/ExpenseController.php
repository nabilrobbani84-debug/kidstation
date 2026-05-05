<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $expenses = Expense::query()
            ->when($request->filled('date'), fn ($query) => $query->whereDate('date', $request->input('date')))
            ->when($request->filled('q'), function ($query) use ($request) {
                $keyword = $request->input('q');

                $query->where(function ($query) use ($keyword) {
                    $query->where('category', 'like', "%{$keyword}%")
                        ->orWhere('description', 'like', "%{$keyword}%");
                });
            })
            ->latest()
            ->get();

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

    public function destroy(Expense $expense)
    {
        $expense->delete();

        return redirect()->route('expenses.index')->with('success', 'Pengeluaran berhasil dihapus');
    }
}
