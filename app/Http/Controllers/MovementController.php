<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Movement;
use App\Models\Product;
use App\Models\ServiceOrder;
use Illuminate\Http\Request;

class MovementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $movements = Movement::with(['customer', 'products.model']) // asume relaciones cargadas
            ->latest()
            ->get();

        return view('movements.index', compact('movements'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $type = $request->query('type', 'ingreso_externo'); // por defecto ingreso_externo

        $clientes = Customer::all();
        $oses = ServiceOrder::all();
        $availableProducts = collect(); // fallback vacío para evitar errores

        if ($type === 'despacho_interno') {
            $availableProducts = Product::whereNull('expanded')
                ->whereHas('serviceOrders', fn($q) => $q->where('marked', true))
                ->whereDoesntHave('movement', fn($q) => $q->where('movement_type', 'despacho_interno'))
                ->with(['model.brand', 'model.designation', 'movement', 'serviceOrders'])
                ->get();
        }

        return view('movements.create', compact('type', 'clientes', 'oses', 'availableProducts'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'cliente_id' => 'required|exists:customers,id',
            'tipo_movimiento' => 'required|string',
            'guia_despacho' => 'required|string',
            'empresa' => 'required|string',
            'bodega' => 'nullable|string',
            'service_order_id' => 'nullable|exists:service_orders,id',
            'modelos' => 'required|array',
            'modelos.*.modelo_id' => 'required|exists:product_models,id',
            'modelos.*.cantidad' => 'required|integer|min:1',
        ]);

        // Crear movimiento
        $movement = Movement::create([
            'customer_id' => $validated['cliente_id'],
            'movement_type' => $validated['tipo_movimiento'],
            'dispatch_guide' => $validated['guia_despacho'] ?? null,
            'company' => $validated['empresa'],
            'warehouse' => $validated['bodega'] ?? null,
            'service_order_id' => $validated['service_order_id'] ?? null,
        ]);

        // Crear productos asociados
        foreach ($validated['modelos'] as $data) {
            Product::create([
                'movement_id' => $movement->id,
                'model_id' => $data['modelo_id'],
                'quantity' => $data['cantidad'],
                'expanded' => null,
                'base_product_id' => null,
            ]);
        }

        return redirect()->route('movements.index')->with('success', 'Movement registered successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
