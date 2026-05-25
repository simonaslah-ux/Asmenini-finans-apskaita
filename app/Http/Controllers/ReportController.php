<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    private function getReportData(Request $request): array
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

        return compact(
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
        );
    }

    public function index(Request $request)
    {
        $data = $this->getReportData($request);

        return view('reports.index', $data);
    }

    public function downloadPdf(Request $request)
    {
        $data = $this->getReportData($request);

        $pdf = Pdf::loadView('reports.pdf', $data);

        return $pdf->download('finansu-ataskaita.pdf');
    }

    public function sendEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $data = $this->getReportData($request);

        $pdf = Pdf::loadView('reports.pdf', $data);

        Mail::send([], [], function ($message) use ($request, $pdf) {
            $message->to($request->email)
                ->subject('Asmeninių finansų ataskaita')
                ->html('Sveiki,<br><br>Prisegame sugeneruotą asmeninių finansų ataskaitą PDF formatu.')
                ->attachData($pdf->output(), 'finansu-ataskaita.pdf', [
                    'mime' => 'application/pdf',
                ]);
        });

        return redirect()->route('reports.index', $request->only([
            'start_date',
            'end_date',
            'category_id'
        ]))->with('success', 'PDF ataskaita išsiųsta el. paštu.');
    }
}