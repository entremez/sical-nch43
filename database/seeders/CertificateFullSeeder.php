<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;
use App\Models\Designation;
use App\Models\ProductModel;
use App\Models\Certificate;

class CertificateFullSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('seeders/data/certificates.csv');
        $rows = array_map('str_getcsv', file($path));
        $header = array_map('trim', array_shift($rows));

        foreach ($rows as $row) {
            $data = array_combine($header, $row);

            // Normalizar marca y denominación
            $brand = Brand::firstOrCreate(['name' => trim($data['marca'])]);
            $designation = Designation::firstOrCreate(['name' => trim($data['producto'])]);

            // Crear certificado una sola vez por fila
            $certificate = Certificate::create([
                'cliente' => $data['cliente'],
                'contacto' => $data['contacto'],
                'direccion' => $data['direccion'],
                'fabrica' => $data['fabrica'],
                'direccion_fabrica' => $data['direccion_fabrica'],
                'certificado' => $data['certificado'],
                'qr' => $data['qr'],
                'area_id' => $data['area'] ?? 1,
                'active' => $data['active'] ?? 1,
            ]);

            // Separar modelos por " / " o " ; "
            $rawModelos = preg_split('/\s*[\/;]\s*|\s+-\s+/', $data['modelo']);
            foreach ($rawModelos as $modelo) {
                $modelo = trim($modelo);
                if (!$modelo) continue;

                $productModel = ProductModel::firstOrCreate([
                    'brand_id' => $brand->id,
                    'designation_id' => $designation->id,
                    'name' => $modelo,
                ]);

                // Relacionar por tabla pivote
                $certificate->productModels()->syncWithoutDetaching([$productModel->id]);

            }
        }
    }
}
