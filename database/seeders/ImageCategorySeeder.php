<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ImageCategory;

class ImageCategorySeeder extends Seeder
{
    public function run()
    {
        ImageCategory::insert([
            [
                'name' => 'Embalaje',
                'description' => 'Fotos del embalaje original del producto',
                'min_images' => 2,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Placa',
                'description' => 'Foto de la placa del equipo',
                'min_images' => 0,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Manual',
                'description' => 'Foto del manual si está presente',
                'min_images' => 0,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Accesorios',
                'description' => 'Fotos de los accesorios incluidos',
                'min_images' => 0,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
