<?php

namespace App\Http\Controllers;

use App\Models\Movement;
use App\Models\Product;
use App\Models\ServiceOrder;
use Illuminate\Http\Request;

class ProductAssignmentController extends Controller
{
    public function assignToServiceOrder(Request $request)
    {
        $guide = $request->query('guide');

        $products = collect();

        if ($guide) {
            $guides = collect(explode(',', $guide))
                ->map(fn($g) => trim($g))->filter()->unique()->toArray();

            $products = Product::with([
                'movement',
                'model.brand',
                'model.designation',
                'serviceOrders',
                'movementProducts.movement' // 🆕 necesario para la ubicación
            ])
                ->whereNull('expanded')
                ->whereNull('external_dispatch_id')
                ->whereHas('movement', fn($q) => $q->whereIn('dispatch_guide', $guides))
                ->get();
        }

        $serviceOrders = ServiceOrder::orderBy('order_number')->get();

        return view('products.assign-to-service-order', compact('products', 'guide', 'serviceOrders'));
    }



    public function storeAssignment(Request $request)
    {
        $request->validate([
            'service_order_id' => 'required|exists:service_orders,id',
            'products' => 'required|array|min:1',
        ]);

        $serviceOrderId = $request->input('service_order_id');
        $productosCreados = [];
        $productosParaAsignar = [];

        foreach ($request->products as $key) {
            if (str_contains($key, '-')) {
                [$originalId, $index] = explode('-', $key);
                $divisiones = explode(',', $request->input("divisiones_$originalId"));

                if (!isset($productosCreados[$originalId])) {
                    $original = Product::findOrFail($originalId);
                    $productosCreados[$originalId] = [];

                    $movimientoBase = $original->movement;

                    // 🔁 Crear un único nuevo movimiento hijo
                    $movimientoHijo = Movement::create([
                        'movement_type' => 'desagregacion',
                        'customer_id' => $movimientoBase?->customer_id,
                        'dispatch_guide' => $movimientoBase?->dispatch_guide,
                        'company' => $movimientoBase?->company,
                        'base_movement_id' => $movimientoBase->id,
                    ]);

                    // 🔁 Guardar referencia cruzada en movimiento padre
                    $movimientoBase->expanded = $movimientoHijo->id;
                    $movimientoBase->save();

                    foreach ($divisiones as $cantidad) {
                        $cantidad = intval($cantidad);

                        $hijo = Product::create([
                            'movement_id' => $movimientoHijo->id,
                            'model_id' => $original->model_id,
                            'quantity' => $cantidad,
                            'expanded' => null,
                            'base_product_id' => $original->id,
                        ]);

                        $productosCreados[$originalId][] = $hijo;
                    }

                    // (opcional) dejar referencia a los productos desagregados en el producto original
                    $original->expanded = json_encode(collect($productosCreados[$originalId])->pluck('id'));
                    $original->save();
                }

                $productosParaAsignar[] = $productosCreados[$originalId][intval($index)];
            } else {
                $productosParaAsignar[] = Product::findOrFail($key);
            }
        }

        // Asignación a la OSE
        foreach ($productosParaAsignar as $product) {
            if (!$product->serviceOrders->contains($serviceOrderId)) {
                $product->serviceOrders()->attach($serviceOrderId);
            }
        }

        return redirect()->route('products.assign')->with('success', 'Productos asignados correctamente.');
    }




    public function unassignedProduct()
    {
        $products = Product::with(['model.brand', 'model.designation', 'movement'])
            ->whereDoesntHave('serviceOrders')
            ->whereNull('expanded') // ignorar productos desagregados
            ->get();

        return view('products.unassigned', compact('products'));
    }

    public function unmarkedProducts()
    {
        $productos = Product::whereHas('serviceOrders', function ($q) {
            $q->where('marked', false);
        })
            ->with(['model.brand', 'model.designation', 'serviceOrders' => function ($q) {
                $q->where('marked', false);
            }])
            ->get();

        return view('products.unmarked_products', compact('productos'));
    }


    public function mark($id)
    {
        $order = ServiceOrder::findOrFail($id);
        $order->marked = true;
        $order->save();

        return response()->json(['success' => true]);
    }
}
