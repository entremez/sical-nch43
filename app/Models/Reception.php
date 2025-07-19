<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reception extends Model
{
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function images()
    {
        return $this->hasMany(ReceptionImage::class);
    }
}
