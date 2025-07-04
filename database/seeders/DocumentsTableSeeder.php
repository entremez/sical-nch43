<?php

namespace Database\Seeders;

use App\Models\Document;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DocumentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $documents = [
            'Certificado tipo',
            'Certificado de aprobación',
            'Certificado de seguimiento',
            'Certificado de producción',
            'Certificado de comercio',
            'Informe de rechazo',
            'Evaluación de conformidad',
            'Declaración mensual',
            'Declaración de conformidad respecto al tipo',
        ];

        foreach ($documents as $name) {
            Document::create(['name' => $name]);
        }
    }
}
