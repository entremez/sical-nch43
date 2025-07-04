<?php

namespace Database\Seeders;

use App\Models\AplicationDocument;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AplicationDocumentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $docs = [
            'Sistema 1 Tipo',
            'Sistema 1 aprobación',
            'Sistema 1 Control regular',
            'Sistema 2 Postulación',
            'Sistema 2 Aprobación',
            'Sistema 2 Control producción',
            'Sistema 2 Control comercio',
            'Sistema 2 Declaración mensual',
            'Sistema 3 aprobación',
            'Sistema 4 Aprobación',
            'Sistema 6 Aprobación',
        ];

        foreach ($docs as $name) {
            AplicationDocument::create(['name' => $name]);
        }
    }
}
