<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Book;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //buat data dummy buku 
        $buku = [
            [
                'nama' => 'pemrograman PHP',
                'pengarang' => 'bunafit nugroho',
                'penerbit' => 'Gava Media'
            ],
            [
                'nama' => 'pemrograman C#',
                'pengarang' => 'Septi',
                'penerbit' => 'Gava Media'
            ],
            [
                'nama' => 'pemrograman C#',
                'pengarang' => 'Septi',
                'penerbit' => 'Gava Media'
            ],
        ];

        //generate ke database 
        foreach ($buku as $item) {
            Book::create($item);
        }
    }
}
