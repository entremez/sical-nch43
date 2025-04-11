<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\V2Controller;
use PhpOffice\PhpSpreadsheet\Spreadsheet; 
use PhpOffice\PhpSpreadsheet\Writer\Xlsx; 

class ExportToExcelController extends Controller
{

    public function toExcel($lote, $muestras, $fila, $columa){
        $data = json_decode(V2Controller::nch43($lote, $muestras, $fila, $columa));
        $spreadsheet = new Spreadsheet(); 
        // Retrieve the current active worksheet 
        $sheet = $spreadsheet->getActiveSheet();
        foreach(range('A','Z') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
            $sheet->getStyle($columnID)->getAlignment()->setHorizontal('center');
        }
        $sheet->setCellValue('A1','Lote'); 
        $sheet->setCellValue('B1',$lote); 
        $sheet->setCellValue('A2','Muestras');
        $sheet->setCellValue('B2',$muestras);
        $sheet->setCellValue('A3','Coordenada inicial');
        $sheet->setCellValue('B3',$fila.' / '.$columa);
        $sheet->setCellValue('A4','Coordenada final');
        $last_number = $data[count($data) - 1];
        $last_row = explode(" | ", $last_number->fila);
        $last_column = explode(" | ",$last_number->columna);
        $sheet->setCellValue('B4',$last_row[count($last_row) -1].'/'.$last_column[count($last_column) -1]);

        $sheet->setCellValue('B6','#'); 
        $sheet->setCellValue('C6','Fila');
        $sheet->setCellValue('D6','Columna');
        $sheet->setCellValue('E6','Número original');
        $sheet->setCellValue('F6','Operación');
        $sheet->setCellValue('G6','Número final');
        $sheet->setCellValue('H6','Comentarios');
        $count = 0;
        foreach($data as $key => $row){
            $valido = '-';
            if($row->valido){
                $count++;
                $valido = $count;
            }
            $sheet->setCellValue('B'.$key+7,$valido);
            $sheet->setCellValue('C'.$key+7,$row->fila);
            $sheet->setCellValue('D'.$key+7,$row->columna);
            $sheet->setCellValue('E'.$key+7,$row->valor_original);
            $sheet->setCellValue('F'.$key+7,$row->operación);
            $sheet->setCellValue('G'.$key+7,$row->valor_final);
            $sheet->setCellValue('H'.$key+7,$row->comentario??='');
        }        
        // Write an .xlsx file  
        $writer = new Xlsx($spreadsheet);
        $name = 'Lote:'.$lote.'_Muestras:'.$muestras;
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . urlencode($name) . '.xlsx"'); 
        // Save .xlsx file to the current directory 
        $writer->save('php://output');
    }
}
