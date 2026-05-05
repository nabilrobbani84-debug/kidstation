<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Expense;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $period = in_array($request->input('period'), ['daily', 'monthly'], true)
            ? $request->input('period')
            : 'daily';
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));
        $selectedDate = Carbon::parse($date);
        
        $salesQuery = Sale::with('product');
        $expensesQuery = Expense::query();

        if ($period === 'monthly') {
            $salesQuery->whereYear('date', $selectedDate->year)->whereMonth('date', $selectedDate->month);
            $expensesQuery->whereYear('date', $selectedDate->year)->whereMonth('date', $selectedDate->month);
        } else {
            $salesQuery->whereDate('date', $selectedDate->format('Y-m-d'));
            $expensesQuery->whereDate('date', $selectedDate->format('Y-m-d'));
        }

        $totalSales = (clone $salesQuery)->sum('total_price');
        $totalExpenses = (clone $expensesQuery)->sum('amount');
        $netProfit = $totalSales - $totalExpenses;

        $sales = $salesQuery->latest()->get();
        $expenses = $expensesQuery->latest()->get();

        return view('reports.index', compact('period', 'date', 'totalSales', 'totalExpenses', 'netProfit', 'sales', 'expenses'));
    }

    public function exportSales(Request $request)
    {
        $period = in_array($request->input('period'), ['daily', 'monthly'], true)
            ? $request->input('period')
            : 'daily';
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));
        $selectedDate = Carbon::parse($date);
        $sales = Sale::with('product')
            ->when(
                $period === 'monthly',
                fn ($query) => $query->whereYear('date', $selectedDate->year)->whereMonth('date', $selectedDate->month),
                fn ($query) => $query->whereDate('date', $selectedDate->format('Y-m-d'))
            )
            ->latest()
            ->get();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="penjualan-'.$period.'-'.$date.'.csv"',
        ];

        $callback = function() use ($sales) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Tanggal', 'Produk', 'Quantity', 'Total']);
            foreach ($sales as $sale) {
                fputcsv($file, [$sale->date, $sale->product?->name ?? 'Produk Terhapus', $sale->quantity, $sale->total_price]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportExpenses(Request $request)
    {
        $period = in_array($request->input('period'), ['daily', 'monthly'], true)
            ? $request->input('period')
            : 'daily';
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));
        $selectedDate = Carbon::parse($date);
        $expenses = Expense::query()
            ->when(
                $period === 'monthly',
                fn ($query) => $query->whereYear('date', $selectedDate->year)->whereMonth('date', $selectedDate->month),
                fn ($query) => $query->whereDate('date', $selectedDate->format('Y-m-d'))
            )
            ->latest()
            ->get();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="pengeluaran-'.$period.'-'.$date.'.csv"',
        ];

        $callback = function() use ($expenses) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Tanggal', 'Kategori', 'Deskripsi', 'Jumlah']);
            foreach ($expenses as $expense) {
                fputcsv($file, [$expense->date, $expense->category, $expense->description, $expense->amount]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
