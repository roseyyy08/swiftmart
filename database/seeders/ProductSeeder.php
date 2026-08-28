<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $minuman = Category::create(['name' => 'Minuman', 'description' => 'Aneka minuman kemasan']);
        $makanan = Category::create(['name' => 'Makanan Ringan', 'description' => 'Snack dan makanan instan']);

        Product::create(['category_id' => $minuman->id, 'name' => 'Teh Botol Sosro', 'barcode' => '8991234560017', 'price' => 5000, 'stock' => 40]);
        Product::create(['category_id' => $minuman->id, 'name' => 'Aqua 600ml', 'barcode' => '8991234560024', 'price' => 4000, 'stock' => 50]);
        Product::create(['category_id' => $minuman->id, 'name' => 'Pocari Sweat', 'barcode' => '8991234560031', 'price' => 8000, 'stock' => 3]);
        Product::create(['category_id' => $makanan->id, 'name' => 'Indomie Goreng', 'barcode' => '8991234560048', 'price' => 3500, 'stock' => 60]);
        Product::create(['category_id' => $makanan->id, 'name' => 'Chitato', 'barcode' => '8991234560055', 'price' => 10000, 'stock' => 4]);
    }
}