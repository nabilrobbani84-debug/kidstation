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
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));
        
        $totalSales = Sale::whereDate('date', $date)->sum('total_price');
        $totalExpenses = Expense::whereDate('date', $date)->sum('amount');
        $netProfit = $totalSales - $totalExpenses;

        $sales = Sale::whereDate('date', $date)->with('product')->get();
        $expenses = Expense::whereDate('date', $date)->get();

        return view('reports.index', compact('date', 'totalSales', 'totalExpenses', 'netProfit', 'sales', 'expenses'));
    }

    public function exportSales(Request $request)
    {
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));
        $sales = Sale::whereDate('date', $date)->with('product')->get();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="penjualan-'.$date.'.csv"',
        ];

        $callback = function() use ($sales) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Tanggal', 'Produk', 'Quantity', 'Total']);
            foreach ($sales as $sale) {
                fputcsv($file, [$sale->date, $sale->product->name, $sale->quantity, $sale->total_price]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportExpenses(Request $request)
    {
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));
        $expenses = Expense::whereDate('date', $date)->get();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="pengeluaran-'.$date.'.csv"',
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
