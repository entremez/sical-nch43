<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;


class ClientController extends Controller
{
    public function getDispatchableProducts($clientId)
    {
        $customer = Customer::with('movements.products.model.brand', 'movements.products.model.designation')
            ->where('id', $clientId)
            ->firstOrFail();

        $allProducts = [];

        // Paso 1: mapa de producto ID => guía de ingreso (heredada si es expansión)
        $inheritedGuides = [];

        foreach ($customer->movements as $movement) {
            foreach ($movement->products as $product) {
                if (!empty($product->expanded)) {
                    $expandedList = is_array($product->expanded) ? $product->expanded : json_decode($product->expanded, true);

                    foreach ($expandedList as $expandedId) {
                        $inheritedGuides[$expandedId] = $movement->dispatch_guide ?? 'N/A';
                    }
                }
            }
        }

        // Paso 2: IDs de productos listados como expandidos
        $expandedIds = collect($inheritedGuides)->keys();

        foreach ($customer->movements as $movement) {
            foreach ($movement->products as $product) {
                // ❌ Omitir productos que tienen su propia expansión
                if (!empty($product->expanded)) continue;

                // ❌ Omitir productos despachados
                if ($product->external_dispatch_id !== null) continue;

                // ✅ Solo mostrar si:
                // - Es un producto expandidos (aparece en expandedIds)
                // - O no es parte de ninguna expansión
                $isPartOfExpansion = $expandedIds->contains($product->id);
                $isStandalone = !$expandedIds->contains($product->id);

                if (!($isPartOfExpansion || $isStandalone)) continue;

                // Guía de ingreso:
                $entryGuide = $inheritedGuides[$product->id] ?? (
                    $movement->movement_type === 'ingreso_externo'
                    ? $movement->dispatch_guide
                    : 'N/A'
                );

                $productModel = $product->loadMissing('movementProducts.movement');

                $lastMovementProduct = $productModel->movementProducts->sortByDesc('id')->first();

                $lastMovementType = $lastMovementProduct?->movement?->movement_type ?? null;

                $location = match ($lastMovementType) {
                    'despacho_interno' => 'En laboratorio',
                    'ingreso_externo', 'ingreso_interno' => 'En bodega',
                    default => 'En bodega - Nunca ha salido',
                };


                $allProducts[] = [
                    'id' => $product->id,
                    'quantity' => $product->quantity,
                    'comments' => $movement->comments ?? '',
                    'entry_guide' => $entryGuide ?? 'N/A',
                    'location' => $location,
                    'in_warehouse' => str_contains($location, 'bodega'),
                    'model' => [
                        'name' => $product->model->name ?? '',
                        'brand' => ['name' => $product->model->brand->name ?? ''],
                        'designation' => ['name' => $product->model->designation->name ?? ''],
                    ],
                ];
            }
        }

        return response()->json($allProducts);
    }
}
