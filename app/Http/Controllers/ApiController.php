<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Muestreo;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


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

        if ($muestreo != null && $muestreo->trazabilidad != null) {
            return redirect()->route('home')->with('error', 'Muestreo ya registrado.');
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

            $from_scp = ApiScpController::show($ose);

            if($from_scp == "llamada")
                return redirect()->back()->with('error', 'OSE sin "Llamada" en SCP.');

            if ($from_scp != null) {

                return view('preview', [
                    'id_proceso' => $from_scp[0],
                    'marca' => $from_scp[3],
                    'modelo' => $from_scp[4],
                    'lote' => $from_scp[10],
                    'muestras' => $from_scp[7],
                    'denominacion' => $from_scp[2],
                    'cliente' => $from_scp[5],
                    'direccion' => $from_scp[6],
                    'trazabilidad' => '',
                    'fecha' => Carbon::parse($from_scp[11])->format('Y-m-d'),
                    'sistema' => $from_scp[8],
                    'contacto' => $from_scp[9]
                ]);
            } else {
                return redirect()->back()->with('error', 'Proceso no encontrado en SCP.');
            }
        }
    }

    public function preview(Request $request)
    {
        $xyz[0] = $request->equis;
        $xyz[1] = $request->ye;
        $xyz[2] = $request->zeta;
        $xyz[3] = isset($request->comentarios) ? $request->comentarios : '';

        $xyz[4] = $this->getInitials(Auth::user()->name);

        $row = $request['row'];
        $column = $request['column'];
        $lote = preg_replace('/[^0-9]/', '', $request['lote']);
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
        return view('trace', [
            'muestreo' => Muestreo::find($id_muestreo),
            'id' => $id_muestreo
        ]);
    }

    public function end(Request $request)
    {
        $muestreo = Muestreo::find($request->id);
        $muestreo->trazabilidad = json_encode($request->trazabilidad);

        $test = json_decode($muestreo->comentarios);
        $firma = json_decode(Auth::user()->sign);
        $test[4] = $test[4]."/".$this->getInitials(Auth::user()->name)."/".$firma[0];
        $test[5] = $firma[1];

        $muestreo->comentarios = json_encode($test);

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
        $sign = json_decode($muestreo['comentarios'])[5] ?? "firma.png";

        $pdf = Pdf::loadView('welcome3', [
            'data' => $data,
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
            'firma' => $sign
        ]);
        $pdf->output();
        $domPdf = $pdf->getDomPDF();
        $canvas = $domPdf->get_canvas();
        $w = $canvas->get_width();


        $initials = json_decode($muestreo['comentarios'])[4] ?? "CD";

        $name = "Muestreo " . $muestreo['id_proceso'] . ".pdf";
        $canvas->page_text($w - 100, 54, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 10, [0, 0, 0]);
        $canvas->page_text(520, 820, $initials, null, 10, [0, 0, 0]);
        return $pdf->setPaper('letter', 'portrait')->download($name);
    }

    public function sistema2($certificado)
    {
        $certificado = Certificate::where('certificado', $certificado)->first();
        $sistema = explode("-", $certificado->certificado)[1];
        return view('preview', [
            'marca' => $certificado->marca,
            'modelo' => $certificado->modelo,
            'denominacion' => $certificado->producto,
            'cliente' => $certificado->cliente,
            'contacto' => $certificado->contacto,
            'direccion' => $certificado->direccion,
            'direccion_fabrica' => $certificado->direccion_fabrica,
            'sistema' => $sistema,
        ]);
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
