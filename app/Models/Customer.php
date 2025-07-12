<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    public function movements()
    {
        return $this->hasMany(Movement::class, 'customer_id', 'id');
    }
}
