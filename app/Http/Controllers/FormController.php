<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FormController extends Controller
{
    public function form(Request $request){
        $row = $request->row;
        if($request->random_row != null)
            $row = $request->random_row;
        $column = $request->column;
        if($request->random_column != null)
            $column = $request->random_column;       
        return redirect()->route('samples', ['lote' => $request->lot, 'muestras' => $request->samples, 'fila' => $row, 'columna' => $column]);
    }

    
}

