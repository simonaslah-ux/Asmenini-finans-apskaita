<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        // Jei nieko nepasirinkta, rodome einamą mėnesį
        $month = $request->month ?? now()->format('Y-m');

        $transactionsQuery = Transaction::with('category')
            ->where('user_id', $userId);

        // Jei pasirinkta ne "all", filtruojame pagal mėnesį
        if ($month !== 'all') {
            $startDate = Carbon::parse($month . '-01')->startOfMonth();
            $endDate = Carbon::parse($month . '-01')->endOfMonth();

            $transactionsQuery->whereBetween('date', [$startDate, $endDate]);
        }

        $totalIncome = (clone $transactionsQuery)
            ->where('type', 'income')
            ->sum('amount');

        $totalExpense = (clone $transactionsQuery)
            ->where('type', 'expense')
            ->sum('amount');

        $balance = $totalIncome - $totalExpense;

        $latestTransactions = (clone $transactionsQuery)
            ->orderBy('date', 'desc')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalIncome',
            'totalExpense',
            'balance',
            'latestTransactions',
            'month'
        ));
    }
}