<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run()
    {
        Brand::insert([
            ['name' => 'Electrolux', 'description' => 'Electrodomésticos'],
            ['name' => 'Fensa', 'description' => 'Línea blanca'],
            ['name' => 'Karcher', 'description' => 'Limpieza industrial'],
        ]);
    }
}