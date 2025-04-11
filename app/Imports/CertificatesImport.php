<?php

namespace App\Imports;

use App\Models\Certificate;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CertificatesImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Certificate([
            "cliente" => $row["cliente"],
            "contacto" => $row["contacto"],
            "direccion" => $row["direccion"],
            "fabrica" => $row["fabrica"],
            "direccion_fabrica" => $row["direccion_fabrica"],
            "producto" => $row["producto"],
            "marca" => $row["marca"],
            "modelo" => $row["modelo"],
            "certificado" => $row["certificado"],
            "qr" => $row["qr"],
            "area" => $row["area"],
            "active" => $row["activo"]
        ]);
    }
}
