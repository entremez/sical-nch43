<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Muestreo;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class ApiController extends Controller
{
    public function search(Request $request, $id = null)
    {
        $ose = $request->search;
        if ($id != null) {
            $muestreo = Muestreo::find($id);
        } else {
            $muestreo = Muestreo::where('id_proceso', 'like', '%' . $ose . '%')->first();
        }

        if ($muestreo != null) {
            return view('preview', [
                'id_proceso' => $muestreo->id_proceso,
                'marca' => $muestreo->marca,
                'modelo' => $muestreo->modelo,
                'lote' => $muestreo->lote,
                'muestras' => $muestreo->muestras,
                'denominacion' => $muestreo->denominacion,
                'sicalito' => $muestreo->sicalito,
                'row' => $muestreo->fila,
                'column' => $muestreo->columna,
                'comentarios' => json_decode($muestreo->comentarios),
                'fecha' => $muestreo->fecha,
                'cliente' => $muestreo->cliente,
                'contacto' => $muestreo->contacto,
                'direccion' => $muestreo->direccion,
                'sistema' => $muestreo->sistema,
            ]);
        } else {
            $response = Http::withHeaders([
                'Accept' => 'application/json'
            ])->get('http://192.168.0.248/api/buscar-ose?ose=' . $ose);

            if ($response->ok()) {
                $data = $response->json();
                //dd($data);
                $data_scp = $data['procesos'][0];
                $data_detalles = $data['detalles'];

                //dd($data_detalles);
                $contacto = $data_detalles['Detalles'][4]['Valor'];
                $lote = $data_detalles['Detalles'][5]['Valor'];
                $sistema = '013';
                $samples = $data_detalles['Cantidad muestras'];

                return view('preview', [
                    'id_proceso' => $data_scp['OSE'],
                    'marca' => $data_scp['Marcas'],
                    'modelo' => $data_scp['Modelos'],
                    'lote' => $lote,
                    'muestras' => $samples,
                    'denominacion' => $data_scp['Denominación_Técnica'],
                    'cliente' => $data_detalles['Cliente'],
                    'direccion' => explode("/ ",$data_detalles['Direccion Marcado'])[1],
                    'sistema' => $data_detalles['Detalles'][0]['Valor'],
                    'trazabilidad' => $data_detalles['Detalles'][1]['Valor'],
                    'fecha' => Carbon::parse($data_detalles['Detalles'][6]['Valor'])->format('Y-m-d'),
                    'sistema' => $sistema,
                    'contacto' => $contacto
                ]);
            } else {
                return view('preview');
            }
        }
    }

    public function preview(Request $request)
    {
        $xyz[0] = $request->equis;
        $xyz[1] = $request->ye;
        $xyz[2] = $request->zeta;
        $xyz[3] = isset($request->comentarios) ? $request->comentarios : '';

        $row = $request['row'];
        $column = $request['column'];
        $lote = $request['lote'];
        $samples = $request['muestras'];

        Muestreo::updateOrCreate(['id_proceso' => $request['id_proceso']], [
            'marca' => $request['marca'],
            'modelo' => $request['modelo'],
            'lote' => $lote,
            'muestras' => $samples,
            'denominacion' => $request['denominacion'],
            'fecha' => $request['fecha'],
            'sicalito' => $request['sicalito'],
            'year' => $request['year'],
            'fila' => $row,
            'columna' => $column,
            'comentarios' => json_encode($xyz),
            'muestreo' => V2Controller::nch43($lote, $samples, $row, $column),
            'cliente' => $request['cliente'],
            'contacto' => $request['contacto'],
            'direccion' => $request['direccion'],
            'sistema' => $request['sistema'],
        ]);

        return redirect()->route('home', ['muestreos' => Muestreo::all()]);

    }

    public function trace($id_muestreo)
    {
        return view('trace', ['muestreo' => Muestreo::find($id_muestreo)]);
    }

    public function end(Request $request)
    {
        $muestreo = Muestreo::find($request->id);
        $muestreo->trazabilidad = json_encode($request->trazabilidad);
        $muestreo->save();
        return redirect()->route('home', ['muestreos' => Muestreo::all()]);
    }

    public function toPdf($id)
    {
        $muestreo = Muestreo::find($id);
        $initial_coordinate = $muestreo->fila . '/' . $muestreo->columna;
        $data = V2Controller::nch43($muestreo->lote, $muestreo->muestras, $muestreo->fila, $muestreo->columna);
        $muestreo->muestreo = $data;
        $muestreo->save();
        $array_data = json_decode($data);
        $last_number = $array_data[count($array_data) - 1];
        $last_row = explode(" | ", $last_number->fila);
        $last_column = explode(" | ", $last_number->columna);

        $pdf = Pdf::loadView('welcome3', [
            'data' => V2Controller::nch43($muestreo->lote, $muestreo->muestras, $muestreo->fila, $muestreo->columna),
            'lote' => $muestreo->lote,
            'muestras' => $muestreo->muestras,
            'inicial' => $initial_coordinate,
            'final' => $last_row[count($last_row) - 1] . '/' . $last_column[count($last_column) - 1],
            'id_proceso' =>  $muestreo['id_proceso'],
            'denominacion' => $muestreo['denominacion'],
            'marcas' => $muestreo['marca'],
            'modelos' => $muestreo['modelo'],
            'sicalito' => intval($muestreo['sicalito']),
            'trazabilidad' => json_decode($muestreo['trazabilidad']),
            'fecha' => $muestreo['fecha'],
            'comentarios' => json_decode($muestreo['comentarios']),
            'cliente' => $muestreo['cliente'],
            'contacto' => $muestreo['contacto'],
            'direccion' => $muestreo['direccion'],
            'sistema' => $muestreo['sistema'],
        ]);
        $pdf->output();
        $domPdf = $pdf->getDomPDF();
        $canvas = $domPdf->get_canvas();
        $w = $canvas->get_width();

        $canvas->page_text($w - 100, 54, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 10, [0, 0, 0]);
        return $pdf->setPaper('letter', 'portrait')->download('nch43.pdf');
    }

    public function sistema2($certificado){
        $certificado = Certificate::where('certificado', $certificado)->first();
        return view('preview', [
            'marca' => $certificado->marca,
            'modelo' => $certificado->modelo,
            'denominacion' => $certificado->producto,
            'cliente' => $certificado->cliente,
            'contacto' => $certificado->contacto,
            'direccion' => $certificado->direccion,
            'direccion_fabrica' => $certificado->direccion_fabrica,
            'sistema' => '022',
        ]);
    }
}
