<?php

namespace App\Http\Controllers;

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

            $products = Product::with(['movement', 'model.brand', 'model.designation', 'serviceOrders'])
                ->whereNull('expanded') // ⬅️ solo productos no desagregados
                ->whereHas('movement', fn($q) => $q->whereIn('dispatch_guide', $guides))
                ->get();
        }

        // 🔧 ESTA ES LA LÍNEA FALTANTE:
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

        $productosCreados = []; // para agrupar hijos por padre
        $productosParaAsignar = [];

        foreach ($request->products as $key) {
            if (str_contains($key, '-')) {
                [$originalId, $index] = explode('-', $key);
                $divisiones = explode(',', $request->input("divisiones_$originalId"));

                // Crear todos los hijos una sola vez si no existen ya
                if (!isset($productosCreados[$originalId])) {
                    $original = Product::findOrFail($originalId);
                    $productosCreados[$originalId] = [];

                    foreach ($divisiones as $i => $cantidad) {
                        $cantidad = intval($cantidad);
                        $hijo = Product::create([
                            'movement_id' => $original->movement_id,
                            'model_id' => $original->model_id,
                            'quantity' => $cantidad,
                            'expanded' => null,
                            'base_product_id' => $original->id,
                        ]);
                        $productosCreados[$originalId][] = $hijo;
                    }

                    // Guardar los IDs hijos en el padre
                    $original->expanded = json_encode(
                        collect($productosCreados[$originalId])->pluck('id')
                    );
                    $original->save();
                }

                // De los hijos ya creados, tomamos solo el que el usuario seleccionó
                $productosParaAsignar[] = $productosCreados[$originalId][intval($index)];
            } else {
                // Producto que no fue desagregado
                $productosParaAsignar[] = Product::findOrFail($key);
            }
        }

        // Asignar solo los productos seleccionados
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
}
