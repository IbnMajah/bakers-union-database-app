<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Bakery;
use App\Models\Transaction;
use Inertia\Inertia;
use Inertia\Response;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $lastMonth = Carbon::now()->subMonth();

        // Get current counts
        $currentExpenses = Transaction::where('type', 'debit')->sum('amount');
        $currentPayments = Transaction::where('type', 'credit')->sum('amount');
        $currentBakeries = Bakery::where('status', 'active')->count();

        // Get last month's counts for percentage calculations
        $lastMonthExpenses = Transaction::where('type', 'debit')
            ->where('created_at', '<', $lastMonth)
            ->sum('amount');
        $lastMonthPayments = Transaction::where('type', 'credit')
            ->where('created_at', '<', $lastMonth)
            ->sum('amount');
        $lastMonthBakeries = Bakery::where('status', 'active')
            ->where('created_at', '<', $lastMonth)
            ->count();

        // Calculate percentage changes
        $expenseChange = $lastMonthExpenses ? round(($currentExpenses - $lastMonthExpenses) / $lastMonthExpenses * 100) : 0;
        $paymentChange = $lastMonthPayments ? round(($currentPayments - $lastMonthPayments) / $lastMonthPayments * 100) : 0;
        $bakeryChange = $lastMonthBakeries ? round(($currentBakeries - $lastMonthBakeries) / $lastMonthBakeries * 100) : 0;

        return Inertia::render('Dashboard/Index', [
            'stats' => [
                [
                    'title' => 'Total Expenses',
                    'numValue' => 'GMD ' . number_format($currentExpenses, 2),
                    'change' => $expenseChange > 0 ? "+$expenseChange% from last month" : "$expenseChange% from last month",
                ],
                [
                    'title' => 'Total Payments',
                    'numValue' => 'GMD ' . number_format($currentPayments, 2),
                    'change' => $paymentChange > 0 ? "+$paymentChange% from last month" : "$paymentChange% from last month",
                ],
                [
                    'title' => 'Active Bakeries',
                    'numValue' => $currentBakeries,
                    'change' => $bakeryChange > 0 ? "+$bakeryChange% from last month" : "$bakeryChange% from last month",
                ],
            ]
        ]);
    }
}
