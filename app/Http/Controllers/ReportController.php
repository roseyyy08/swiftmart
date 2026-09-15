<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Bangun query transaksi yang sudah kena filter (dipakai bareng
     * oleh index() dan exportPdf() supaya logika filter-nya sama persis).
     */
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
        // <== BARU
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

    /**
     * Export PDF TANPA package tambahan (tidak butuh composer require).
     *
     * Caranya: render halaman HTML khusus yang di-style buat kertas
     * (bukan dark theme admin), lalu browser sendiri yang "print to PDF"
     * lewat window.print() -> pilih "Save as PDF" di dialog print.
     * Ini valid karena semua browser modern punya fitur print-to-pdf
     * bawaan, jadi tidak perlu library PDF (dompdf/mpdf) di server.
     */
    public function exportPdf(Request $request)
    {
        $query = $this->filteredQuery($request);

        // Ambil SEMUA data yang kena filter (tanpa pagination),
        // karena PDF laporan harus nampilin semua baris, bukan 10 per halaman.
        $transactions = $query->latest()->get();

        $totalPendapatan = $transactions->sum('total');
        $totalTransaksi = $transactions->count();
        $rataRata = $totalTransaksi > 0 ? $totalPendapatan / $totalTransaksi : 0;

        return view('reports.export-pdf', compact(
            'transactions', 'totalPendapatan', 'totalTransaksi', 'rataRata'
        ));
    }
}