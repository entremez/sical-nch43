<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Certificate;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class CertificateSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('seeders/data/certificates.csv');
        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0); // Usa la primera fila como encabezado

        foreach ($csv->getRecords() as $record) {
            Certificate::create([
                'cliente' => $record['cliente'],
                'contacto' => $record['contacto'],
                'direccion' => $record['direccion'],
                'fabrica' => $record['fabrica'],
                'direccion_fabrica' => $record['direccion_fabrica'],
                'producto' => $record['producto'],
                'marca' => $record['marca'],
                'modelo' => $record['modelo'],
                'certificado' => $record['certificado'],
                'qr' => $record['qr'],
                'area' => $record['area'],
                'active' => $record['active'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

