<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Member;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class SelfCheckoutController extends Controller
{
    public function index()
    {
         $token = config('services.kiosk_token'); // <== ganti dari Str::random(6)

    // <== BARU: cuma bikin cart kosong kalau BELUM ada isinya sama sekali
    // (jadi refresh laptop nggak akan nge-reset keranjang yang lagi jalan)
    if (!cache()->has("kiosk_{$token}")) {
        cache()->forever("kiosk_{$token}", ['cart' => [], 'member' => null]); // <== forever, bukan addMinutes(30)
    }

    return view('checkout.index', compact('token'));
    }

    // <== BARU: dipanggil pas HP buka link pairing-nya
    public function scanDevice($token)
    {
        // <== BARU: nggak perlu abort 404 lagi, kalau belum ada ya dibikinin aja
    if (!cache()->has("kiosk_{$token}")) {
        cache()->forever("kiosk_{$token}", ['cart' => [], 'member' => null]);
    }

    return view('checkout.scan-device', compact('token'));
    }

    // <== BARU: dipanggil layar laptop tiap beberapa detik buat "ngintip" papan pengumuman
    public function cartState($token)
    {
        $state = cache()->get("kiosk_{$token}", ['cart' => [], 'member' => null]);
        return response()->json($state);
    }

    public function scan(Request $request)
{
    // <== FIX: validasi dulu. Kalau HP kirim request aneh (token/barcode kosong),
    // langsung ditolak di sini dengan pesan yang jelas, bukan lanjut jalan terus error.
    $request->validate([
        'token' => 'required|string',
        'barcode' => 'required|string',
    ]);

    $key = "kiosk_{$request->token}";
    $state = cache()->get($key, ['cart' => [], 'member' => null]);

    $product = Product::where('barcode', $request->barcode)->first();

    if (!$product) {
        return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan!']);
    }

    // <== FIX: produk nonaktif nggak boleh ikut kejual di kasir
    if (!$product->is_active) {
        return response()->json(['success' => false, 'message' => 'Produk ini sedang tidak dijual!']);
    }

    $qtyDiKeranjang = $state['cart'][$product->id]['quantity'] ?? 0;

    // <== FIX: cek stok pas scan juga, biar customer tahu dari awal
    // kalau barangnya udah mau habis, bukan pas mau bayar baru ketauan.
    if ($qtyDiKeranjang + 1 > $product->stock) {
        return response()->json(['success' => false, 'message' => "Stok {$product->name} tidak cukup! Sisa stok: {$product->stock}"]);
    }

    if (isset($state['cart'][$product->id])) {
        $state['cart'][$product->id]['quantity']++;
    } else {
        $state['cart'][$product->id] = [
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => 1,
        ];
    }

    cache()->forever($key, $state); // <== FIX: simpen $state yang baru, jangan di-reset kosong

    return response()->json(['success' => true, 'cart' => $state['cart']]);
    }

    public function updateCart(Request $request)
    {
        // <== FIX: quantity wajib angka >= 0, product_id wajib ada
        $request->validate([
            'token' => 'required|string',
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:0',
        ]);

        $key = "kiosk_{$request->token}";
        $state = cache()->get($key, ['cart' => [], 'member' => null]);

        $productId = $request->product_id;
        $quantity = $request->quantity;

        if ($quantity <= 0) {
            unset($state['cart'][$productId]);
        } else {
            // <== FIX: cek stok juga di sini, soalnya laptop bisa langsung
            // ketik angka quantity berapapun tanpa lewat proses scan.
            $product = Product::find($productId);
            if ($product && $quantity > $product->stock) {
                return response()->json(['success' => false, 'message' => "Stok {$product->name} cuma tersisa {$product->stock}!", 'cart' => $state['cart']]);
            }

            $state['cart'][$productId]['quantity'] = $quantity;
        }

        cache()->forever($key, $state); // <== FIX: sama, simpen $state, jangan di-reset kosong

        return response()->json(['success' => true, 'cart' => $state['cart']]);
    }

    public function checkMember(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'phone' => 'required|string',
        ]);

        $key = "kiosk_{$request->token}";
        $state = cache()->get($key, ['cart' => [], 'member' => null]);

        $member = Member::where('phone', $request->phone)->first();

        if (!$member) {
            return response()->json(['success' => false, 'message' => 'Member tidak ditemukan!']);
        }

        $state['member'] = $member->toArray();
        cache()->forever($key, $state); // <== FIX: forever, bukan addMinutes(30)

        return response()->json(['success' => true, 'member' => $member]);
    }

    public function process(Request $request)
    {
        // <== FIX: payment_method wajib salah satu dari 3 metode yang didukung,
        // bukan string bebas apapun yang dikirim.
        $request->validate([
            'token' => 'required|string',
            'payment_method' => 'required|in:cash,debit,qris',
        ]);

        $key = "kiosk_{$request->token}";
        $state = cache()->get($key, ['cart' => [], 'member' => null]);

        $cart = $state['cart'];
        $member = $state['member'];

        if (empty($cart)) {
            return response()->json(['success' => false, 'message' => 'Keranjang masih kosong!']);
        }

        // <== FIX: cek stok SEMUA item dulu sebelum nyimpen apapun ke database.
        // Ini jaga-jaga kalau stok berubah di antara waktu scan dan waktu bayar
        // (misal 2 customer checkout barang yang sama hampir bersamaan).
        foreach ($cart as $productId => $item) {
            $product = Product::find($productId);

            if (!$product) {
                return response()->json(['success' => false, 'message' => 'Salah satu produk di keranjang sudah tidak ada!']);
            }

            if ($item['quantity'] > $product->stock) {
                return response()->json(['success' => false, 'message' => "Stok {$product->name} tidak cukup! Sisa stok: {$product->stock}"]);
            }
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // <== FIX: bungkus semua proses simpan (transaksi + detail + potong stok + poin)
        // dalam SATU DB::transaction(). Artinya: kalau salah satu langkah gagal
        // di tengah jalan, SEMUA langkah sebelumnya ikut dibatalkan otomatis
        // (rollback) -> nggak ada data "setengah jadi" (misal transaksi kesimpen
        // tapi stok belum kepotong).
        $transaction = DB::transaction(function () use ($cart, $member, $total, $request) {
            $transaction = Transaction::create([
                'member_id' => $member['id'] ?? null,
                'invoice' => 'INV-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6)),
                'total' => $total,
                'payment_method' => $request->payment_method,
            ]);

            foreach ($cart as $productId => $item) {
                $transaction->details()->create([
                    'product_id' => $productId,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);

                Product::where('id', $productId)->decrement('stock', $item['quantity']);
            }

            if ($member) {
                $poin = intdiv($total, 10000);
                Member::where('id', $member['id'])->increment('points', $poin);
            }

            return $transaction;
        });

        cache()->forever($key, ['cart' => [], 'member' => null]); // <== ini yang bener taruh di sini (satu-satunya tempat buat reset ke kosong)

        return response()->json([
            'success' => true,
            'redirect' => route('checkout.receipt', $transaction->id),
        ]);
    }

    public function receipt(Transaction $transaction)
    {
        $transaction->load('details.product', 'member');
        return view('checkout.receipt', compact('transaction'));
    }
}