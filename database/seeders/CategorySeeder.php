<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //data dummy category
        $categories = [
            ['name' => 'Minuman' ],
            ['name' => 'Makanan'],
            ['name' => 'Snack'],
            ['name' => 'Beras'],
            ['name' => 'Sabun'],
        ];

        foreach ($categories as $c) {
            Category::create($c);
        }
    }
}
