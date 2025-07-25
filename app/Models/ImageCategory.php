<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImageCategory extends Model
{
    protected $fillable = [
        'name',
        'description',
        'min_images',
        'active',
    ];
}
