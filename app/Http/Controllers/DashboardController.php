<?php

namespace App\Http\Controllers;

use \App\Models\Category;
use \App\Models\Product;
use \App\Models\Member;
use \App\Models\Transaction;
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

        return view('dashboard', compact(
            'totalProduk',
            'totalKategori',
            'totalMember',
            'transaksiHariIni',
            'pendapatanHariIni'
        ));
    
    }
}
