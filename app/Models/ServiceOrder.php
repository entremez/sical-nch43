<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceOrder extends Model
{
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_service_order', 'service_order_id', 'product_id');
    }
}
