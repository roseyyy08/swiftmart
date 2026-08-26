<?php

namespace App\Http\Controllers;

use App\Models\Product; 
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->paginate(10);
        return view('products.index', compact('products'));
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
}