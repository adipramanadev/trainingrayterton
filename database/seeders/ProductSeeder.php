<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $catId = Category::pluck('id','name'); // ['Minuman'=>1, ...] tergantung DB
        //make 10 item product
        $products = [
            ['sku' => 'A001', 'name' => 'Air Mineral 600ml', 'category_id' => 1, 'price' => 500, 'stock' => 100],
            ['sku' => 'A002', 'name' => 'Teh Botol 450ml', 'category_id' => 1, 'price' => 600, 'stock' => 80],
            ['sku' => 'A003', 'name' => 'Kopi Kapal Api 100g', 'category_id' => 2, 'price' => 150, 'stock' => 50],
            ['sku' => 'A004', 'name' => 'Gula Pasir 1kg', 'category_id' => 2, 'price' => 140, 'stock' => 60],
            ['sku' => 'A005', 'name' => 'Mie Instan Goreng', 'category_id' => 3, 'price' => 350, 'stock' => 200],
            ['sku' => 'A006', 'name' => 'Mie Instan Kuah', 'category_id' => 3, 'price' => 350, 'stock' => 150],
            ['sku' => 'A007', 'name' => 'Susu UHT 250ml', 'category_id' => 1, 'price' => 700, 'stock' => 90],
            ['sku' => 'A008', 'name' => 'Beras Premium 5kg', 'category_id' => 4, 'price' => 650, 'stock' => 40],
            ['sku' => 'A009', 'name' => 'Minyak Goreng 1L', 'category_id' => 4, 'price' => 180, 'stock' => 70],
            ['sku' => 'A010', 'name' => 'Sabun Mandi Cair 500ml', 'category_id' => 1, 'price' => 120, 'stock' => 60],
        ];

        foreach ($products as $p) {
            Product::updateOrCreate(
                ['sku' => $p['sku']],
                [
                    'name' => $p['name'],
                    'category_id' => $p['category_id'], // aman kalau kategori belum ada
                    'price' => $p['price'],
                    'stock' => $p['stock'],
                ]
            );
            // Product::create($p);
        }
    }
}
