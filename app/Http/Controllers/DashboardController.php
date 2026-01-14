<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Expense;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        
        $totalSales = Sale::sum('total_price');
        $totalExpenses = Expense::sum('amount');
        $netProfit = $totalSales - $totalExpenses;
        $transactionCount = Sale::count();

        // Chart Data (Last 7 Days)
        $chartData = [];
        $dates = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $dates[] = Carbon::now()->subDays($i)->format('d/m');
            
            $chartData['sales'][] = Sale::whereDate('date', $date)->sum('total_price');
            $chartData['expenses'][] = Expense::whereDate('date', $date)->sum('amount');
        }

        $recentTransactions = Sale::with('product')->latest()->take(5)->get();

        return view('dashboard', compact('totalSales', 'totalExpenses', 'netProfit', 'transactionCount', 'chartData', 'dates', 'recentTransactions'));
    }
}
