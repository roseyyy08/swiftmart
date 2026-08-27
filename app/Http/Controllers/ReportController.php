<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with('member', 'details');

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $transactions = $query->latest()->paginate(10)->withQueryString();

        $totalPendapatan = (clone $query)->sum('total');
        $totalTransaksi = (clone $query)->count();

        return view('reports.index', compact('transactions', 'totalPendapatan', 'totalTransaksi'));
    }
}