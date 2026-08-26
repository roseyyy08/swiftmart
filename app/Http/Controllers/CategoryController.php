<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::paginate(10);
        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categories.create');
    }
 
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
        ], [
            'name.required' => 'Nama harus diisi!',
            'description.required' => 'Description harus diisi!',
        ]);

        Category::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Kategori berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    // public function show(string $id)
    // {
    //     $category = Category::findOrfail($id);
    //     return view();
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = Category::findOrFail($id);
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate(
            [
                'name' => 'required',
                'description' => 'required',
            ],

            [
                'name.required' => "nama harus diisi!",
                'description.required' => 'Description harus diisi!',

            ]
            );
            $category = Category::findOrFail($id);
            $category->name= $request->name;
            $category->description = $request->description;

            $category->save();
            return redirect()
              ->route('categories.index')
              ->with('success', 'Kategori berhasil diubah!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()
          ->route('categories.index')
          ->with('success', 'Kategori berhasil dihapus');
    }
}
