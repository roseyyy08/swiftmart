<?php

namespace App\Http\Controllers;

use App\Models\Product; 
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('barcode', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->stock_filter === 'low') {
            $query->where('stock', '<=', 5);
        } elseif ($request->stock_filter === 'empty') {
            $query->where('stock', 0);
        }

        $products = $query->paginate(10)->withQueryString();

        $totalProduk = Product::count();
        $stokMenipis = Product::where('stock', '<=', 5)->count();
        $nonaktif = Product::where('is_active', false)->count();
        $categories = \App\Models\Category::all();

        return view('products.index', compact('products', 'totalProduk', 'stokMenipis', 'nonaktif', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required',
            'barcode' => 'required|unique:products,barcode',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'image' => 'nullable|image|max:2048',
        ], [
            'category_id.required' => 'Kategori harus dipilih!',
            'name.required' => 'Nama produk harus diisi!',
            'barcode.required' => 'Barcode harus diisi!',
            'barcode.unique' => 'Barcode sudah dipakai produk lain!',
            'price.required' => 'Harga harus diisi!',
            'stock.required' => 'Stok harus diisi!',
            'image.image' => 'File harus berupa gambar!',
        ]);

        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
        }

        Product::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'barcode' => $request->barcode,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $path,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required',
            'barcode' => 'required|unique:products,barcode,' . $id,
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'image' => 'nullable|image|max:2048',
        ], [
            'category_id.required' => 'Kategori harus dipilih!',
            'name.required' => 'Nama produk harus diisi!',
            'barcode.required' => 'Barcode harus diisi!',
            'barcode.unique' => 'Barcode sudah dipakai produk lain!',
            'price.required' => 'Harga harus diisi!',
            'stock.required' => 'Stok harus diisi!',
            'image.image' => 'File harus berupa gambar!',
        ]);

        $product = Product::findOrFail($id);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $product->image = $request->file('image')->store('products', 'public');
        }

        $product->category_id = $request->category_id;
        $product->name = $request->name;
        $product->barcode = $request->barcode;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->is_active = $request->has('is_active');
        $product->save();

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil diubah!');
    }

    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil dihapus');
    }

    /**
     * Dipanggil AJAX pas admin scan barcode di modal "Terima Stok Masuk".
     * Tugasnya cuma CARI produk berdasarkan barcode yang di-scan -
     * belum ngubah apa-apa ke database, cuma ngasih tau admin
     * "ini barcode punya produk apa, stok sekarang berapa".
     */
    public function restockLookup(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string',
        ]);

        $product = Product::where('barcode', $request->barcode)->first();

        if (!$product) {
            // <== BARU: barcode belum terdaftar sama sekali di database.
            // Ini kejadian normal kalau produknya beneran baru pertama kali
            // masuk toko (bukan restock produk lama) - admin diarahkan ke
            // form Tambah Produk biasa, bukan dianggap error.
            return response()->json(['found' => false]);
        }

        return response()->json([
            'found' => true,
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'barcode' => $product->barcode,
                'stock' => $product->stock,
            ],
        ]);
    }

    /**
     * Dipanggil setelah admin scan barcode (ketemu produknya) DAN
     * udah ngisi berapa jumlah yang masuk dari dus/kardus. Ini yang
     * BENERAN nambahin stok - pakai increment(), bukan nge-timpa angka,
     * jadi nggak perlu itung manual "stok lama + stok baru" sendiri.
     */
    public function restockConfirm(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $product->increment('stock', $request->qty);

        return response()->json([
            'success' => true,
            'name' => $product->name,
            'new_stock' => $product->fresh()->stock,
        ]);
    }
}