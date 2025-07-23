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
    public function index(Request $request)
    {
        $products = Product::with(['model.brand', 'model.designation', 'movementProducts.movement', 'reception'])
            ->when($request->boolean('hideDispatched'), function ($query) {
                    $query->whereNull('external_dispatch_id');
                })
            ->get()
            ->filter(function ($product) {
                // Excluir productos que fueron base de una expansión
                return empty($product->expanded);
            })
            ->map(function ($product) {
                $lastMovement = $product->movementProducts->sortByDesc('id')->first();

                $location = match ($lastMovement?->movement?->movement_type) {
                    'despacho_interno', 'despacho_externo' => 'Fuera de bodega',
                    'ingreso_externo', 'ingreso_interno' => 'En bodega',
                    default => 'En bodega'
                };

                return [
                    'brand' => $product->model->brand->name ?? '-',
                    'model' => $product->model->name ?? '-',
                    'designation' => $product->model->designation->name ?? '-',
                    'quantity' => $product->quantity,
                    'location' => $location,
                    'guide' => $product->movement->dispatch_guide,
                    'dispatched' => $product->external_dispatch_id !== null,
                    'history' => $product->history(),
                    'has_reception' => $product->reception !== null,
                    'movement_id' => $product->movement_id,
                ];
            });
        return view('movements.index', [
            'products' => $products,
            'hideDispatched' => $request->boolean('hideDispatched', false),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $type = $request->query('type', 'ingreso_externo');

        $clientes = Customer::all();
        $oses = ServiceOrder::all();
        $availableProducts = collect();

        if ($type === 'despacho_interno') {
            $oses = ServiceOrder::where('marked', true)->orderBy('order_number')->get();
            $clientes = Customer::where('type', 'internal')->get();
            return view('movements.internal-dispatch', compact('type', 'clientes', 'oses'));
        }

        if ($type === 'ingreso_interno') {
            $clientes = Customer::where('type', 'internal')->get();

            // 🧠 Buscar OSEs con productos cuyo último movimiento es un despacho interno
            $oses = ServiceOrder::where('marked', true)
                ->whereHas('products', function ($query) {
                    $query->whereNull('expanded')
                        ->whereExists(function ($sub) {
                            $sub->selectRaw(1)
                                ->from('movement_product as mp')
                                ->join('movements as m', 'm.id', '=', 'mp.movement_id')
                                ->whereColumn('mp.product_id', 'products.id')
                                ->orderByDesc('m.created_at')
                                ->limit(1)
                                ->where('m.movement_type', 'despacho_interno');
                        });
                })
                ->orderBy('order_number')
                ->get();

            return view('movements.internal-entry', compact('type', 'clientes', 'oses'));
        }

        if ($type === 'despacho_externo') {
            $clientes = Customer::where('type', 'external')->get();
            $oses = ServiceOrder::whereHas('products', function ($query) {
                $query->whereNull('expanded')
                    ->whereExists(function ($sub) {
                        $sub->selectRaw(1)
                            ->from('movement_product as mp')
                            ->join('movements as m', 'm.id', '=', 'mp.movement_id')
                            ->whereColumn('mp.product_id', 'products.id')
                            ->orderByDesc('m.created_at')
                            ->limit(1)
                            ->whereIn('m.movement_type', ['ingreso_interno', 'ingreso_externo']);
                    });
            })
                ->orderBy('order_number')
                ->get();
            $availableProducts = collect(); // modelos se agregan a mano

            return view('movements.external-dispatch', compact('type', 'clientes', 'oses'));
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

    public function storeInternalDispatch(Request $request)
    {
        $request->validate([
            'cliente_id_internal' => 'required|exists:customers,id',
            'products' => 'required|array|min:1',
            'products.*' => 'exists:products,id'
        ]);

        $movement = Movement::create([
            'customer_id' => $request->cliente_id_internal,
            'movement_type' => 'despacho_interno',
        ]);

        $movement->internalProducts()->attach($request->products);

        return redirect()->route('movements.index')
            ->with('success', 'Despacho interno registrado correctamente.');
    }

    public function storeInternalEntry(Request $request)
    {
        $request->validate([
            'cliente_id_internal' => 'required|exists:customers,id',
            'products' => 'required|array|min:1',
            'products.*' => 'exists:products,id'
        ]);

        $movement = Movement::create([
            'customer_id' => $request->cliente_id_internal,
            'movement_type' => 'ingreso_interno',
        ]);

        $movement->internalProducts()->attach($request->products);

        return redirect()->route('movements.index')->with('success', 'Ingreso interno registrado correctamente.');
    }

    public function storeExternalDispatch(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:customers,id',
            'dispatch_guide' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'products' => 'required|array|min:1',
            'products.*' => 'required|exists:products,id',
        ]);

        $movement = Movement::create([
            'customer_id' => $request->client_id,
            'movement_type' => 'despacho_externo',
            'dispatch_guide' => $request->dispatch_guide,
            'company' => $request->company,
        ]);

        foreach ($request->products as $productId) {
            $product = Product::findOrFail($productId);
            $product->external_dispatch_id = $movement->id;
            $product->save();

            $movement->internalProducts()->attach($productId);
        }

        return redirect()->route('movements.index')->with('success', 'Despacho externo registrado correctamente.');
    }


    public function getProductsForMovement($id)
    {
        $movement = Movement::with([
            'products.model.brand',
            'products.model.designation',
            'movementProducts.model.brand',
            'movementProducts.model.designation',
            'movementProducts.serviceOrders'
        ])->findOrFail($id);

        // Combina ambos sets
        $productsFromDirect = $movement->products;
        $productsFromPivot = $movement->movementProducts;


        $allProducts = $productsFromDirect->merge($productsFromPivot)->values();

        return response()->json($allProducts);
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
