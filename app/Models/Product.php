<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Product extends Model
{
    protected $fillable = [
        'movement_id',
        'model_id',
        'quantity',
        'expanded',
        'base_product_id'
    ];

    protected $appends = ['location', 'disponible'];

    public function movement()
    {
        return $this->belongsTo(Movement::class);
    }

    public function movements()
    {
        return $this->belongsToMany(Movement::class, 'movement_product')->withTimestamps();
    }

    public function internalMovements()
    {
        return $this->belongsToMany(Movement::class, 'movement_product');
    }


    /**
     * Relación con el modelo de producto (product_models).
     */
    public function model()
    {
        return $this->belongsTo(ProductModel::class);
    }

    /**
     * Relación con su producto base, si este producto es una subdivisión.
     */
    public function baseProduct()
    {
        return $this->belongsTo(Product::class, 'base_product_id');
    }

    /**
     * Relación inversa: productos expandidos que derivan de este producto.
     */
    public function expandedProducts()
    {
        return $this->hasMany(Product::class, 'base_product_id');
    }

    public function serviceOrders()
    {
        return $this->belongsToMany(ServiceOrder::class, 'product_service_order', 'product_id', 'service_order_id')->withTimestamps();
    }

    public function lastMovement()
    {
        return $this->hasOne(Movement::class)->latestOfMany();
    }

    public function movementProducts()
    {
        return $this->hasMany(MovementProduct::class);
    }

    public function lastMovementProduct()
    {
        return $this->hasOne(MovementProduct::class)->latestOfMany('id');
    }

    public function history(): string
    {
        $events = [];

        // Fecha de ingreso (desde base si corresponde)
        $ingreso = $this->baseProduct?->created_at ?? $this->created_at;
        $events[] = $ingreso->format('d-m-Y') . ' - Ingreso a bodega';

        // Si fue desagregado
        if ($this->base_product_id) {
            $events[] = $this->created_at->format('d-m-Y') . ' - Desagregado de producto base';
        }

        // OSE asignadas
        foreach ($this->serviceOrders as $order) {
            $events[] = ($order->pivot?->created_at?->format('d-m-Y') ?? 'Sin fecha') . ' - Asignado a ' . $order->order_number;
            if ($order->marked) {
                $events[] = $order->updated_at?->format('d-m-Y') . ' - Producto marcado';
            }
        }

        // Movimientos
        foreach ($this->movementProducts as $mp) {
            $type = $mp->movement?->movement_type;
            $fecha = $mp->movement?->created_at;
            if ($type && $fecha) {
                $tipo = ucfirst(str_replace('_', ' ', $type));
                if ($tipo == "Despacho externo")
                    $tipo = "Despacho externo con guía " . $mp->movement->dispatch_guide;
                $events[] = $fecha->format('d-m-Y') . " - Movimiento: $tipo";
            }
        }

        return implode("\n", $events);
    }

    public function location(): Attribute
    {
        return Attribute::get(function () {
            if ($this->lastMovementProduct === null) {
                return 'bodega';
            }
            $lastType = $this->lastMovementProduct->movement->movement_type ?? null;
            return match ($lastType) {
                'ingreso_externo' => 'bodega',
                'ingreso_interno' => 'bodega',
                'desagregacion' => 'bodega',
                'despacho_interno' => 'laboratorio',
                'despacho_externo' => 'despachado',
                default => 'desconocido',
            };
        });
    }

    public function disponible(): Attribute
    {
        return Attribute::get(function () {
            $lastType = $this->lastMovementProduct->movement->movement_type ?? null;

            // Está disponible si aún no se ha despachado externamente
            return $lastType !== 'despacho_externo';
        });
    }

    public function reception()
    {
        return $this->hasOne(Reception::class);
    }
}
