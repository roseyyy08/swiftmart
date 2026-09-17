<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    private function filteredQuery(Request $request)
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
        if ($request->tipe_pelanggan === 'member') {
            $query->whereNotNull('member_id');
        } elseif ($request->tipe_pelanggan === 'guest') {
            $query->whereNull('member_id');
        }

        return $query;
    }

    public function index(Request $request)
    {
        $query = $this->filteredQuery($request);

        $transactions = $query->latest()->paginate(10)->withQueryString();

        $totalPendapatan = (clone $query)->sum('total');
        $totalTransaksi = (clone $query)->count();
        $rataRata = $totalTransaksi > 0 ? $totalPendapatan / $totalTransaksi : 0;

        return view('reports.index', compact('transactions', 'totalPendapatan', 'totalTransaksi', 'rataRata'));
    }

    public function exportPdf(Request $request)
    {
        $query = $this->filteredQuery($request);

        $transactions = $query->latest()->get();

        $totalPendapatan = $transactions->sum('total');
        $totalTransaksi = $transactions->count();
        $rataRata = $totalTransaksi > 0 ? $totalPendapatan / $totalTransaksi : 0;

        return view('reports.export-pdf', compact(
            'transactions', 'totalPendapatan', 'totalTransaksi', 'rataRata'
        ));
    }
}