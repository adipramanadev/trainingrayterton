<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            [
                'namecustomer' => 'John Doe',
                'address' => 'Jl. Merdeka No. 123, Jakarta Selatan'
            ],
            [
                'namecustomer' => 'Jane Smith',
                'address' => 'Jl. Sudirman No. 45, Jakarta Pusat'
            ],
            [
                'namecustomer' => 'Robert Johnson',
                'address' => 'Jl. Gatot Subroto No. 67, Jakarta Barat'
            ],
            [
                'namecustomer' => 'Mary Williams',
                'address' => 'Jl. Asia Afrika No. 89, Bandung'
            ],
            [
                'namecustomer' => 'Michael Brown',
                'address' => 'Jl. Pahlawan No. 12, Surabaya'
            ],
            [
                'namecustomer' => 'Sarah Davis',
                'address' => 'Jl. Diponegoro No. 34, Semarang'
            ],
            [
                'namecustomer' => 'James Wilson',
                'address' => 'Jl. Ahmad Yani No. 56, Medan'
            ],
            [
                'namecustomer' => 'Patricia Moore',
                'address' => 'Jl. Veteran No. 78, Yogyakarta'
            ],
            [
                'namecustomer' => 'Richard Taylor',
                'address' => 'Jl. Pemuda No. 90, Malang'
            ],
            [
                'namecustomer' => 'Linda Anderson',
                'address' => 'Jl. Gajah Mada No. 23, Denpasar'
            ]
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
}