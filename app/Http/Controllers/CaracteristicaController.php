<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Protocol;
use App\Models\CharacteristicTemplate;

class CaracteristicaController extends Controller
{
    public function porProtocolo($codigo)
    {
        $protocol = Protocol::where('name', $codigo)->first();

        if (! $protocol || !$protocol->category_id) {
            return response()->json(['error' => 'Protocolo no encontrado o sin categoría.'], 404);
        }

        $templates = CharacteristicTemplate::where('category_id', $protocol->category_id)->get();

        return response()->json($templates);
    }
}
