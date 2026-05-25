<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $categoryId = $request->category_id;

        $query = Transaction::with('category')
            ->where('user_id', $userId);

        if ($startDate) {
            $query->whereDate('date', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('date', '<=', $endDate);
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $transactions = $query->orderBy('date', 'desc')->get();

        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpense;

        $minAmount = $transactions->min('amount') ?? 0;
        $maxAmount = $transactions->max('amount') ?? 0;
        $avgAmount = $transactions->avg('amount') ?? 0;

        $categories = Category::where('user_id', $userId)->get();

        $categorySummary = $transactions
            ->groupBy(function ($transaction) {
                return $transaction->category->name ?? 'Be kategorijos';
            })
            ->map(function ($items, $categoryName) {
                return [
                    'category' => $categoryName,
                    'total' => $items->sum('amount'),
                ];
            })
            ->values();

        return view('reports.index', compact(
            'transactions',
            'categories',
            'startDate',
            'endDate',
            'categoryId',
            'totalIncome',
            'totalExpense',
            'balance',
            'minAmount',
            'maxAmount',
            'avgAmount',
            'categorySummary'
        ));
    }
}