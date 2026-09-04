<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Member;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProduk = Product::count();
        $totalKategori = Category::count();
        $totalMember = Member::count();
        $transaksiHariIni = Transaction::whereDate('created_at', today())->count();
        $pendapatanHariIni = Transaction::whereDate('created_at', today())->sum('total');
        $stokMenipis = Product::where('stock', '<=', 5)->count();

        $trenPenjualan = collect(range(6, 0))->map(function ($daysAgo) {
            $date = now()->subDays($daysAgo);
            return [
                'label' => $date->translatedFormat('D'),
                'total' => Transaction::whereDate('created_at', $date)->sum('total'),
            ];
        });
        $maxTren = $trenPenjualan->max('total') ?: 1;

        $produkTerlaris = TransactionDetail::selectRaw('product_id, SUM(quantity) as total_qty, SUM(subtotal) as total_omzet')
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->with('product')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalProduk',
            'totalKategori',
            'totalMember',
            'transaksiHariIni',
            'pendapatanHariIni',
            'stokMenipis',
            'trenPenjualan',
            'maxTren',
            'produkTerlaris'
        ));
    }
}