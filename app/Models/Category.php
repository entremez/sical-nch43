<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function area()
    {
        return $this->belongsTo(Area::class);
    }
}
