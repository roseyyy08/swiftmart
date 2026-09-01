<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Member;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SelfCheckoutController extends Controller
{
    public function index()
    {
        // <== BARU: bikin token unik tiap kali layar checkout dibuka,
        // dan siapin "papan pengumuman" kosong buat token ini
        $token = Str::random(6);
        cache()->put("kiosk_{$token}", ['cart' => [], 'member' => null], now()->addMinutes(30));

        return view('checkout.index', compact('token'));
    }

    // <== BARU: dipanggil pas HP buka link pairing-nya
    public function scanDevice($token)
    {
        if (!cache()->has("kiosk_{$token}")) {
            abort(404, 'Sesi checkout tidak ditemukan atau sudah kadaluarsa. Buka ulang layar checkout di kiosk.');
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
        $key = "kiosk_{$request->token}"; // <== BARU
        $state = cache()->get($key, ['cart' => [], 'member' => null]); // <== BARU (ganti dari session)

        $product = Product::where('barcode', $request->barcode)->first();

        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan!']);
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

        cache()->put($key, $state, now()->addMinutes(30)); // <== BARU (ganti dari session)

        return response()->json(['success' => true, 'cart' => $state['cart']]);
    }

    public function updateCart(Request $request)
    {
        $key = "kiosk_{$request->token}"; // <== BARU
        $state = cache()->get($key, ['cart' => [], 'member' => null]); // <== BARU

        $productId = $request->product_id;
        $quantity = $request->quantity;

        if ($quantity <= 0) {
            unset($state['cart'][$productId]);
        } else {
            $state['cart'][$productId]['quantity'] = $quantity;
        }

        cache()->put($key, $state, now()->addMinutes(30)); // <== BARU

        return response()->json(['success' => true, 'cart' => $state['cart']]);
    }

    public function checkMember(Request $request)
    {
        $key = "kiosk_{$request->token}"; // <== BARU
        $state = cache()->get($key, ['cart' => [], 'member' => null]); // <== BARU

        $member = Member::where('phone', $request->phone)->first();

        if (!$member) {
            return response()->json(['success' => false, 'message' => 'Member tidak ditemukan!']);
        }

        $state['member'] = $member; // <== BARU (ganti dari session)
        cache()->put($key, $state, now()->addMinutes(30)); // <== BARU

        return response()->json(['success' => true, 'member' => $member]);
    }

    public function process(Request $request)
    {
        $key = "kiosk_{$request->token}"; // <== BARU
        $state = cache()->get($key, ['cart' => [], 'member' => null]); // <== BARU

        $cart = $state['cart'];
        $member = $state['member'];

        if (empty($cart)) {
            return response()->json(['success' => false, 'message' => 'Keranjang masih kosong!']);
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

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

        cache()->forget($key); // <== BARU (ganti dari session()->forget)

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