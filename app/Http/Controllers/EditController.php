<?php

namespace App\Http\Controllers;

use App\Models\Muestreo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EditController extends Controller
{
    public function edit($id)
    {
        $muestreo = Muestreo::findOrFail($id);
        return view('edit', ['texto' => $muestreo]);
    }

    public function update(Request $request)
    {
        $muestreo = Muestreo::findOrFail($request->id);
        $muestreo->cliente = $request->cliente;
        $muestreo->contacto = $request->contacto;
        $muestreo->direccion = $request->direccion;
        $muestreo->sistema = $request->sistema;
        $muestreo->marca = $request->marca;
        $muestreo->modelo = $request->modelo;
        $muestreo->lote = $request->lote;
        $muestreo->denominacion = $request->denominacion;
        $muestreo->fecha = $request->fecha;

        $xyz[0] = $request->equis;
        $xyz[1] = $request->ye;
        $xyz[2] = $request->zeta;
        $xyz[3] = isset($request->comentarios) ? $request->comentarios : '';

        $xyz[4] = json_decode($muestreo->comentarios)[4]."/".$this->getInitials(Auth::user()->name);
        $xyz[5] = json_decode($muestreo->comentarios)[5];

        $muestreo->comentarios = json_encode($xyz);
        $muestreo->trazabilidad = json_encode($request->trazabilidad);
        $muestreo->save();
        
        return redirect()->route('home', ['muestreos' => Muestreo::all()]);
    }

    public function delete($id)
    {
        $muestreo =  Muestreo::findOrFail($id);
        $muestreo->delete();
        return redirect()->route('home', ['muestreos' => Muestreo::all()]);
    }

    private function getInitials($name)
    {
        $name = trim($name);
        $words = explode(' ', $name);
        $initials = '';

        foreach ($words as $word) {
            $initials .= strtolower(mb_substr($word, 0, 1));
        }

        return $initials;
    }   
}
