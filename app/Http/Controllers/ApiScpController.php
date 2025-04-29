<?php

namespace App\Http\Controllers;

use App\Models\Contacto;
use App\Models\Direccion;
use App\Models\IS_OSESCAB;


class ApiScpController extends Controller
{
    static function show($ose)
    {
        $ingreso = IS_OSESCAB::where('NbrOSE', 'like', '%' . $ose . '%')
        ->with('cliente')
        ->with('llamada')
        ->with('solicitud')
        ->with('marcaModelo')
        ->with('protocolo')
        ->first();

        if($ingreso->llamada == null){
            return "llamada";
        }

        if ($ingreso == null) {
            return null;
        }
    
        $id_direccion = $ingreso->llamada->IdDireccion;

        $direccion = Direccion::where('IdDireccion', $id_direccion)->with(['region'])->first();

        $salida = collect();

        $salida->push($ingreso->NbrOSE);
        $salida->push($ingreso->protocolo->Codigo);
        $salida->push($ingreso->protocolo->Producto);

        $marcas = '';
        $modelo = '';

        foreach ($ingreso->marcaModelo as $item) {
            if($item->Estado == 1){
                $marcas .= $item->Marca . ' / ';
                $modelo .= $item->Modelo . ' / ';
            }
        }

        $marcas = rtrim($marcas, '/ ');
        $modelo = rtrim($modelo, '/ ');

        $salida->push($marcas);
        $salida->push($modelo); 

        $salida->push($ingreso->cliente->RazonSocial);
        $salida->push(trim($direccion->Calle . $direccion->Nro . ', ' . $direccion->region->NOMBRE . ', ' . $direccion->Pais));
        $salida->push($ingreso->llamada->Cantidad);


        $grupos = $ingreso->solicitud->filter(function ($item) {
            return $item->IdGrupo == 1 || $item->IdGrupo == 3 || $item->IdGrupo == 1007 || $item->IdGrupo == 2;
        });

        $valoresBuscados = [];


        foreach ($grupos as $solicitud) {
            $datosGrupo = json_decode($solicitud->DatosGrupo, true); // Lo convertimos a array
        
            if (is_array($datosGrupo)) {
                foreach ($datosGrupo as $dato) {
                    if (isset($dato['Nombre']) && isset($dato['Valor'])) {
                        // Verifica si el nombre es uno de los que te interesan
                        if (in_array($dato['Nombre'], ['Sistema de Certificación', 'Nombre contacto', 'Tamaño de partida (Seguimiento)', 'Tamaño de partida', 'Dirección del Fabricante'])) {
                            $valoresBuscados[$dato['Nombre']] = $dato['Valor'];
                        }
                    }
                }
            }
        }

        $pais_fabricacion = Direccion::where('IdDireccion', $valoresBuscados['Dirección del Fabricante'])->first()->Pais;


        $codigo = $pais_fabricacion == "Chile" ? '1':'3'; //Ver cuando sea sistema 2 en SCP
        $sistema = explode(" ", $valoresBuscados['Sistema de Certificación']);

        $salida->push('0'.$sistema[1].$codigo);
        $salida->push(Contacto::where('IdContacto', $valoresBuscados['Nombre contacto'])->value('NombreCompleto'));
        if(isset($valoresBuscados['Tamaño de partida (Seguimiento)'])){
            $salida->push($valoresBuscados['Tamaño de partida (Seguimiento)']);
        }else{
            $salida->push($valoresBuscados['Tamaño de partida']);
        }
        $salida->push($ingreso->llamada->FechaVisita);

        return $salida;
    }
}
