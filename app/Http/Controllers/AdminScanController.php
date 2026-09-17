<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminScanController extends Controller
{
    public function page($token)
    {
        return view('admin-scan.page', compact('token'));
    }

    public function push(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'barcode' => 'required|string',
        ]);

        cache()->put("admin_scan_{$request->token}", [
            'barcode' => $request->barcode,
            'ts' => microtime(true),
        ], now()->addMinutes(15));

        return response()->json(['success' => true]);
    }

    public function poll($token)
    {
        $data = cache()->get("admin_scan_{$token}");

        return response()->json($data ?? ['barcode' => null, 'ts' => 0]);
    }
}