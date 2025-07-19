<?php

namespace App\Http\Controllers;

use App\Models\Movement;
use App\Models\Reception;
use Illuminate\Http\Request;

class ReceptionController extends Controller
{

    public function create(Movement $movement)
    {
        $products = $movement->products()->with('model.brand', 'model.designation')->get();

        return view('receptions.create', compact('movement', 'products'));
    }

    public function store(Request $request, Movement $movement)
    {
        foreach ($request->input('products') as $productId => $data) {
            $reception = Reception::create([
                'product_id' => $productId,
                'report' => $data['report'] ?? '',
            ]);

            if (isset($data['images'])) {
                foreach ($data['images'] as $image) {
                    $path = $image->store('receptions', 'public');
                    $reception->images()->create(['path' => $path]);
                }
            }
        }

        return redirect()->route('movements.index')->with('success', 'Recepción registrada con éxito.');
    }
}
