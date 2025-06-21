<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'movement_id',
        'model_id',
        'quantity',
        'expanded',
        'base_product_id'
    ];

    public function movement()
    {
        return $this->belongsTo(Movement::class);
    }

    public function movements()
    {
        return $this->belongsToMany(Movement::class, 'movement_product');
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
        return $this->belongsToMany(ServiceOrder::class, 'product_service_order', 'product_id', 'service_order_id');
    }
}
