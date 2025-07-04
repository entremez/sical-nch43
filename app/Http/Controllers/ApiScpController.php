<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Contacto;
use App\Models\Direccion;
use App\Models\IS_OSESCAB;
use App\Models\IS_TEXTOS;
use GuzzleHttp\Client;
use Illuminate\Http\Client\Request;

class ApiScpController extends Controller
{
    static function show($ose)
    {
        $ingreso = IS_OSESCAB::where('NbrOSE', 'like', '%' . $ose . '%')
            ->with('cliente')
            ->with('llamada.direccion.region')
            ->with('solicitud')
            ->with('marcaModelo')
            ->with('protocolo')
            ->first();

        if ($ingreso == null) {
            return null;
        }

        if ($ingreso->llamada == null) {
            return "llamada";
        }

        $direccion = $ingreso->llamada->direccion;

        $marcas = '';
        $modelos = '';

        $marcas = $ingreso->marcaModelo
            ->where('Estado', 1)
            ->map(fn($item) => $item->Marca)
            ->implode(' / ');

        $modelos = $ingreso->marcaModelo
            ->where('Estado', 1)
            ->map(fn($item) => $item->Modelo)
            ->implode(' / ');

        // Define los grupos válidos y los campos que te interesan
        $gruposValidos = [1, 2, 3, 1007];
        $camposInteres = [
            'Sistema de Certificación',
            'Nombre contacto',
            'Tamaño de partida (Seguimiento)',
            'Tamaño de partida',
            'Dirección del Fabricante'
        ];

        $valoresBuscados = [];

        // Filtra solo las solicitudes con grupos válidos
        $grupos = $ingreso->solicitud->whereIn('IdGrupo', $gruposValidos);

        foreach ($grupos as $solicitud) {
            $datosGrupo = json_decode($solicitud->DatosGrupo, true); // Lo convertimos a array

            if (is_array($datosGrupo)) {
                foreach ($datosGrupo as $dato) {
                    if (
                        isset($dato['Nombre'], $dato['Valor']) &&
                        in_array($dato['Nombre'], $camposInteres)
                    ) {
                        $valoresBuscados[$dato['Nombre']] = $dato['Valor'];
                    }
                }
            }
        }


        $pais_fabricacion = Direccion::where('IdDireccion', $valoresBuscados['Dirección del Fabricante'])->first()->Pais;

        $codigo = $pais_fabricacion == "Chile" ? '1' : '3'; //Ver cuando sea sistema 2 en SCP
        $sistema = explode(" ", $valoresBuscados['Sistema de Certificación']);

        $nombre_contacto = Contacto::where('IdContacto', $valoresBuscados['Nombre contacto'])->value('NombreCompleto');
        $tamano_partida = $valoresBuscados['Tamaño de partida (Seguimiento)'] ?? $valoresBuscados['Tamaño de partida'];




        $salidaArray = [
            $ingreso->NbrOSE,
            $ingreso->protocolo->Codigo,
            $ingreso->protocolo->Producto,
            $marcas,
            $modelos,
            $ingreso->cliente->RazonSocial,
            trim($direccion->Calle . ' ' . $direccion->Nro . ', ' . $direccion->region->NOMBRE . ', ' . $direccion->Pais),
            $ingreso->llamada->Cantidad,
            '0' . $sistema[1] . $codigo,
            $nombre_contacto,
            $tamano_partida,
            $ingreso->llamada->FechaVisita
        ];

        return collect($salidaArray);
    }

    static function getClientesDesdeApi()
    {
        return Cliente::pluck('RazonSocial', 'Rut',);
    }

    static function getFabricasDesdeApi()
    {
        return IS_TEXTOS::where('TipoMensaje', 6)->with('direccion')->get();
    }

    public function clientes(Request $request)
    {
        $query = $request->get('q', '');
        $clientes = collect($this->getClientesDesdeApi()) // tu fuente externa
            ->filter(fn($c) => str_contains(strtolower($c['Texto']), strtolower($query)))
            ->take(15)
            ->map(fn($c) => [
                'id' => $c['IdRelated'],
                'nombre' => $c['Texto'],
                'direccion' => $c['direccion']['Calle'] ?? '',
            ]);

        return response()->json($clientes);
    }

    public function factories(Request $request)
    {
        $query = $request->get('q', '');
        $fabricas = collect($this->getFabricasDesdeApi()) // tu fuente externa
            ->filter(fn($f) => str_contains(strtolower($f['Texto']), strtolower($query)))
            ->take(15)
            ->map(fn($f) => [
                'id' => $f['IdRelated'],
                'nombre' => $f['Texto'],
                'direccion' => $f['direccion']['Calle'] ?? '',
            ]);

        return response()->json($fabricas);
    }
}
