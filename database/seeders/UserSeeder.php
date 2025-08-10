<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //user seeder
        $users = [
            [
                'name' => 'Cashier Name',
                'email' => 'cashier@example.com',
                'password' => bcrypt('password')

            ],
            [
                'name' => 'Manager Name',
                'email' => 'manager@example.com',
                'password' => bcrypt('password')
            ]
        ];

        foreach ($users as $user) {
            \App\Models\User::create($user);
        }

    }
}
