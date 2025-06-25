<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::insert([
            ['name' => 'Electrolux', 'rut' => '89.123.456-k', 'type' => 'external'],
            ['name' => 'Marcelo Espinoza', 'rut' => '16.378.822-5', 'type' => 'internal'],
            ['name' => 'Andrés Zuárez', 'rut' => '17.378.822-5', 'type' => 'internal'],
            ['name' => 'Karcher', 'rut' => '89.123.457-k', 'type' => 'external'],
            ['name' => 'Midea Carrier', 'rut' => '89.123.457-0', 'type' => 'external'],
        ]);
    }
}