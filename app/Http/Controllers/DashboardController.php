<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Expense;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today();
        $chartRange = (int) $request->input('chart_range', 7);
        $chartRange = in_array($chartRange, [7, 30], true) ? $chartRange : 7;
        
        $totalSales = Sale::sum('total_price');
        $totalExpenses = Expense::sum('amount');
        $netProfit = $totalSales - $totalExpenses;
        $transactionCount = Sale::count();

        // Chart Data
        $chartData = [
            'sales' => [],
            'expenses' => [],
        ];
        $dates = [];
        for ($i = $chartRange - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $dates[] = Carbon::now()->subDays($i)->format('d/m');
            
            $chartData['sales'][] = Sale::whereDate('date', $date)->sum('total_price');
            $chartData['expenses'][] = Expense::whereDate('date', $date)->sum('amount');
        }

        $recentTransactions = Sale::with('product')->latest()->take(5)->get();

        $hasChartData = collect($chartData['sales'])
            ->merge($chartData['expenses'])
            ->contains(fn ($value) => (float) $value > 0);

        return view('dashboard', compact('totalSales', 'totalExpenses', 'netProfit', 'transactionCount', 'chartData', 'dates', 'chartRange', 'hasChartData', 'recentTransactions'));
    }
}
