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
            ['name' => 'PLB-123', 'brand_id' => 1, 'designation_id' => 1],
            ['name' => 'PLR-456', 'brand_id' => 2, 'designation_id' => 1],
            ['name' => 'PCI01P075FR', 'brand_id' => 3, 'designation_id' => 2],
            ['name' => 'HID-45', 'brand_id' => 3, 'designation_id' => 2]
        ]);
    }
}
