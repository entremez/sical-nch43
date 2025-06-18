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
        return $this->activeProducts->sum('quantity');
    }
}
