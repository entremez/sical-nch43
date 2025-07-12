<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
    protected $fillable = ['customer_id', 'movement_type', 'dispatch_guide', 'service_order_id', 'company', 'warehouse'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function internalProducts()
    {
        return $this->belongsToMany(Product::class, 'movement_product');
    }


    public function activeProducts()
    {
        return $this->hasMany(Product::class)
            ->where(function ($q) {
                $q->whereNull('expanded')   // solo padres no desagregados
                    ->orWhereNotNull('base_product_id'); // o hijos
            });
    }


    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function getTotalQuantityAttribute()
    {
        if ($this->movement_type == 'despacho_interno' || $this->movement_type == 'despacho_externo') {
            return "- " . $this->internalProducts()->sum('quantity');
        }
        if ($this->movement_type == 'ingreso_interno') {
            return "+ " . $this->internalProducts()->sum('quantity');
        }
        return "+ " . $this->activeProducts->sum('quantity');
    }

    public function baseMovement()
    {
        return $this->belongsTo(Movement::class, 'base_movement_id');
    }

    public function expandedMovements()
    {
        return $this->hasMany(Movement::class, 'base_movement_id');
    }

    public function parent()
    {
        return $this->belongsTo(Movement::class, 'base_movement_id');
    }

    public function child()
    {
        return $this->belongsTo(Movement::class, 'expanded');
    }

    public function movementProducts()
    {
        return $this->belongsToMany(Product::class, 'movement_product', 'movement_id', 'product_id')
            ->with('model.brand', 'model.designation', 'serviceOrders');
    }

    public function pivotMovementProduct()
    {
        return $this->hasMany(MovementProduct::class, 'movement_id', 'id');
    }
}
