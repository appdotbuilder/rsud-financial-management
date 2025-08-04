<?php

namespace App\Http\Controllers;

use App\Models\BasAccount;
use App\Models\Budget;
use App\Models\HospitalUnit;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display the main financial dashboard.
     */
    public function index()
    {
        $currentYear = date('Y');
        $currentMonth = date('m');
        
        // Get financial summary data
        $totalCashBalance = Transaction::approved()
            ->where('type', 'income')
            ->sum('amount') - Transaction::approved()
            ->where('type', 'expense')
            ->sum('amount');

        $monthlyIncome = Transaction::approved()
            ->where('type', 'income')
            ->whereYear('transaction_date', $currentYear)
            ->whereMonth('transaction_date', $currentMonth)
            ->sum('amount');

        $monthlyExpense = Transaction::approved()
            ->where('type', 'expense')
            ->whereYear('transaction_date', $currentYear)
            ->whereMonth('transaction_date', $currentMonth)
            ->sum('amount');

        $totalBudget = Budget::approved()
            ->where('fiscal_year', $currentYear)
            ->sum('amount');

        $budgetRealization = Transaction::approved()
            ->whereYear('transaction_date', $currentYear)
            ->sum('amount');

        // Get monthly trends for charts
        $monthlyTrends = [];
        for ($i = 1; $i <= 12; $i++) {
            $income = Transaction::approved()
                ->where('type', 'income')
                ->whereYear('transaction_date', $currentYear)
                ->whereMonth('transaction_date', $i)
                ->sum('amount');
            
            $expense = Transaction::approved()
                ->where('type', 'expense')
                ->whereYear('transaction_date', $currentYear)
                ->whereMonth('transaction_date', $i)
                ->sum('amount');

            $monthlyTrends[] = [
                'month' => $i,
                'income' => $income,
                'expense' => $expense,
            ];
        }

        // Get recent transactions
        $recentTransactions = Transaction::with(['basAccount', 'hospitalUnit', 'creator'])
            ->latest()
            ->limit(10)
            ->get();

        // Get budget vs realization by account type
        $budgetComparison = BasAccount::withSum(['budgets' => function ($query) use ($currentYear) {
                $query->approved()->where('fiscal_year', $currentYear);
            }], 'amount')
            ->withSum(['transactions' => function ($query) use ($currentYear) {
                $query->approved()->whereYear('transaction_date', $currentYear);
            }], 'amount')
            ->where('level', 1)
            ->get()
            ->map(function ($account) {
                return [
                    'name' => $account->name,
                    'budget' => $account->budgets_sum_amount ?? 0,
                    'realization' => $account->transactions_sum_amount ?? 0,
                ];
            });

        return Inertia::render('welcome', [
            'financialSummary' => [
                'totalCashBalance' => $totalCashBalance,
                'monthlyIncome' => $monthlyIncome,
                'monthlyExpense' => $monthlyExpense,
                'totalBudget' => $totalBudget,
                'budgetRealization' => $budgetRealization,
                'budgetUtilization' => $totalBudget > 0 ? ($budgetRealization / $totalBudget) * 100 : 0,
            ],
            'monthlyTrends' => $monthlyTrends,
            'recentTransactions' => $recentTransactions,
            'budgetComparison' => $budgetComparison,
        ]);
    }
}