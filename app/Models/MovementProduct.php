<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovementProduct extends Model
{
    protected $table = 'movement_product';
    public function movement()
    {
        return $this->belongsTo(Movement::class);
    }
}
