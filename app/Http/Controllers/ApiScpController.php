<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Contacto;
use App\Models\Direccion;
use App\Models\IS_OSESCAB;
use App\Models\IS_TEXTOS;
use GuzzleHttp\Client;
use Illuminate\Http\Request;


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
        $query = strtolower($request->get('q', ''));

        $clientes = $this->getClientesDesdeApi();

        // Filtrar antes de mapear
        $filtrados = collect($clientes)->filter(function ($nombre, $rut) use ($query) {
            return str_contains(strtolower($nombre), $query) || str_contains(strtolower($rut), $query);
        });

        $result = $filtrados->map(function ($nombre, $rut) {
            return [
                'id' => $rut,
                'text' => $nombre . ' (' . $rut . ')',
            ];
        })->values();

        return response()->json($result);
    }

    public function factories(Request $request)
    {
        $query = strtolower($request->get('q', ''));

        $fabricas = collect($this->getFabricasDesdeApi())
            ->filter(function ($f) use ($query) {
                $nombre = strtolower($f['Texto'] ?? '');
                $direccion = strtolower($f['direccion']['Calle'] ?? '');
                return str_contains($nombre, $query) || str_contains($direccion, $query);
            })
            ->take(15)
            ->map(function ($f) {
                $nombre = $f['Texto'] ?? '';
                $direccion = $f['direccion']['Calle'] ?? '';
                $numero = $f['direccion']['Nro'] ?? '';
                $pais = $f['direccion']['Pais'] ?? '';

                $direccionCompleta = trim(implode(', ', array_filter([$direccion, $numero, $pais])));

                return [
                    'id' => $f['IdRelated'],
                    'nombre' => $nombre,
                    'direccion' => $direccionCompleta,
                    'text' => $nombre . ' (' . $direccionCompleta . ')',
                ];
            })
            ->values();

        return response()->json($fabricas);
    }

    public function protocolosPorArea(Request $request)
    {
        $area = $request->get('area');
        $search = strtolower($request->get('q', ''));

        $protocolos = \App\Models\Protocolo::where('TipoServicio', $area)
            ->where('Estado', 1)
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->whereRaw('LOWER(Codigo) LIKE ?', ["%{$search}%"])
                        ->orWhereRaw('LOWER(Producto) LIKE ?', ["%{$search}%"]);
                });
            })
            ->select('IdProtocolo', 'Codigo', 'Producto')
            ->limit(20)
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->IdProtocolo,
                    'text' => $p->Codigo . ' - ' . $p->Producto,
                    'name' => $p->Codigo, // necesario para Select2 
                ];
            });

        return response()->json($protocolos);
    }

    public function protocolosPorAreaYCategoria(Request $request)
    {
        $area = $request->get('area_id');
        $category = $request->get('category_id');

        // Codigos con categoría asignada
        $asignados = \App\Models\Protocol::where('category_id', $category)
            ->pluck('name')
            ->map(fn($c) => trim(strtoupper($c)))
            ->toArray();

        $protocolos = \App\Models\Protocolo::where('TipoServicio', $area)
            ->where('Estado', 1)
            ->get()
            ->unique('Codigo')
            ->map(function ($p) use ($asignados) {
                $codigo = strtoupper($p->Codigo);
                return [
                    'id' => $p->IdProtocolo,
                    'text' => $codigo . ' - ' . $p->Producto,
                    'checked' => in_array($codigo, $asignados),
                ];
            })
            ->values();

        return response()->json($protocolos);
    }
}
