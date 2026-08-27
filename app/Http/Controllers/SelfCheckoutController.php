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
        $cart = session('cart', []);
        $member = session('member');
        return view('checkout.index', compact('cart', 'member'));
    }

    public function scan(Request $request)
    {
        $product = Product::where('barcode', $request->barcode)->first();

        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan!']);
        }

        $cart = session('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity']++;
        } else {
            $cart[$product->id] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
            ];
        }

        session(['cart' => $cart]);

        return response()->json(['success' => true, 'cart' => $cart]);
    }

    public function updateCart(Request $request)
    {
        $cart = session('cart', []);
        $productId = $request->product_id;
        $quantity = $request->quantity;

        if ($quantity <= 0) {
            unset($cart[$productId]);
        } else {
            $cart[$productId]['quantity'] = $quantity;
        }

        session(['cart' => $cart]);

        return response()->json(['success' => true, 'cart' => $cart]);
    }

    public function checkMember(Request $request)
    {
        $member = Member::where('phone', $request->phone)->first();

        if (!$member) {
            return response()->json(['success' => false, 'message' => 'Member tidak ditemukan!']);
        }

        session(['member' => $member]);

        return response()->json(['success' => true, 'member' => $member]);
    }

    public function process(Request $request)
    {
        $cart = session('cart', []);
        $member = session('member');

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

        session()->forget(['cart', 'member']);

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