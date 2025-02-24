<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Expense;
use App\Models\Bakery;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportExport;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportsController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Reports/Index', [
            'transactionStats' => $this->getTransactionStats(),
            'expenseStats' => $this->getExpenseStats(),
            'summaryStats' => [
                'weekly' => $this->getSummaryStats('week'),
                'monthly' => $this->getSummaryStats('month'),
                'yearly' => $this->getSummaryStats('year'),
            ],
        ]);
    }

    private function getTransactionStats()
    {
        // Get data for the last 30 days
        $startDate = Carbon::now()->subDays(30);

        $stats = Bakery::select('bakeries.name')
            ->selectRaw('SUM(transactions.amount) as total_amount')
            ->leftJoin('accounts', 'bakeries.id', '=', 'accounts.bakery_id')
            ->leftJoin('transactions', 'accounts.id', '=', 'transactions.account_id')
            ->where('transactions.created_at', '>=', $startDate)
            ->where('transactions.type', 'credit') // Only count payments received
            ->groupBy('bakeries.id', 'bakeries.name')
            ->orderBy('total_amount', 'desc')
            ->get();

        return [
            'labels' => $stats->pluck('name'),
            'data' => $stats->pluck('total_amount'),
        ];
    }

    private function getExpenseStats()
    {
        // Get data for the last 30 days
        $startDate = Carbon::now()->subDays(30);

        $stats = Expense::select(DB::raw('DATE(expense_date) as date'))
            ->selectRaw('SUM(amount) as total_amount')
            ->where('expense_date', '>=', $startDate)
            ->where('status', 'approved')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'labels' => $stats->pluck('date')->map(fn($date) => Carbon::parse($date)->format('M d')),
            'data' => $stats->pluck('total_amount'),
        ];
    }

    private function getSummaryStats($groupBy)
    {
        $groupBy = match($groupBy) {
            'weekly' => 'week',
            'monthly' => 'month',
            'yearly' => 'year',
            default => $groupBy
        };

        $endDate = Carbon::now();
        $startDate = match($groupBy) {
            'week' => $endDate->copy()->subWeeks(11),
            'month' => $endDate->copy()->subMonths(11),
            'year' => $endDate->copy()->subYears(4),
        };

        $periodFormat = match($groupBy) {
            'week' => 'DATE_FORMAT(created_at, "%Y-%u")',
            'month' => 'DATE_FORMAT(created_at, "%Y-%m")',
            'year' => 'YEAR(created_at)',
        };

        // Get transactions and expenses combined
        $stats = DB::table(DB::raw('
            (SELECT created_at, amount FROM transactions
             UNION ALL
             SELECT expense_date as created_at, -amount FROM expenses WHERE status = "approved") as combined
        '))
        ->select(
            DB::raw("$periodFormat as period"),
            DB::raw('SUM(amount) as net_amount')
        )
        ->whereBetween('created_at', [$startDate, $endDate])
        ->groupBy('period')
        ->orderBy('period')
        ->get();

        $format = match($groupBy) {
            'week' => function($date) {
                $parts = explode('-', $date);
                $date = Carbon::now()->setISODate($parts[0], $parts[1]);
                return 'Week ' . $date->weekOfYear . ', ' . $date->year;
            },
            'month' => function($date) {
                return Carbon::createFromFormat('Y-m', $date)->format('M Y');
            },
            'year' => function($date) {
                return $date;
            },
        };

        return [
            'labels' => $stats->pluck('period')->map(fn($date) => is_callable($format) ? $format($date) : $date),
            'data' => $stats->pluck('net_amount'),
        ];
    }

    public function generate($type, $summaryType = 'monthly', $format = 'pdf')
    {
        $data = match($type) {
            'transactions' => $this->getTransactionStats(),
            'expenses' => $this->getExpenseStats(),
            'summary' => $this->getSummaryStats($summaryType),
        };

        if ($format === 'pdf') {
            $pdf = PDF::loadView("reports.$type", [
                'data' => $data,
                'type' => $type,
                'summaryType' => $summaryType
            ]);
            return $pdf->download("$type-report.pdf");
        }

        return Excel::download(new ReportExport($data, $type), "$type-report.xlsx");
    }
}
