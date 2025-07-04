<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AplicationDocument;
use App\Models\Activity;

class AplicationDocumentActivitySeeder extends Seeder
{
    public function run(): void
    {
        // IDs de ejemplo: puedes reemplazarlos por lógica más dinámica
        $map = [
            1 => [1], // Sistema 1 Tipo → actividades 1, 2, 3
            2 => [2, 6, 7, 10],    // Sistema 1 Aprobación → actividades 4, 5
            3 => [2, 6, 7, 10], // Sistema 1 Control Regular → actividades 2, 3, 6
            5 => [1, 3, 4], 
            6 => [4],
            7 => [2, 6, 7, 10],
            8 => [9],
            9 => [1],
        ];

        foreach ($map as $aplicationDocumentId => $activityIds) {
            $doc = AplicationDocument::find($aplicationDocumentId);
            if ($doc) {
                $doc->activities()->sync($activityIds);
            }
        }
    }
}

