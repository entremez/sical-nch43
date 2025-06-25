<?php

namespace App\Helpers;

class ProcedimientoHelper
{
    protected static $csvData = null;

    public static function getProcedimientoByLote($lote)
    {
        // Solo carga el CSV una vez
        if (is_null(self::$csvData)) {
            $csvPath = public_path('procedimiento.csv');
            $csv = array_map('str_getcsv', file($csvPath));
            $headers = ['min', 'max', 'digits', 'move', 'divider'];

            self::$csvData = array_filter(array_map(function ($row) use ($headers) {
                if (!is_numeric($row[0])) return null; // omitir fila no válida
                return array_combine($headers, $row);
            }, $csv));
        }

        // Buscar el procedimiento según el lote
        foreach (self::$csvData as $row) {
            $min = (int) $row['min'];
            $max = (int) $row['max'];
            if ($lote >= $min && $lote <= $max) {
                return (object) [
                    'min' => $min,
                    'max' => $max,
                    'digits' => (int) $row['digits'],
                    'move' => $row['move'],
                    'divider' => (int) $row['divider'],
                ];
            }
        }

        return null;
    }
}
