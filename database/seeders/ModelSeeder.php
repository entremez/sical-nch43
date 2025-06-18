<?php

namespace Database\Seeders;

use App\Models\ProductModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class ModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductModel::insert([
            ['name' => 'Plancha', 'brand_id' => 1, 'designation_id' => 1],
            ['name' => 'Plancha', 'brand_id' => 2, 'designation_id' => 1],
        ]);
    }
}
