<?php

namespace App\Models;

use App\Models\TechnicalCharacteristic;
use Illuminate\Database\Eloquent\Model;

class CharacteristicTemplate extends Model
{
    protected $fillable = ['category_id', 'nombre', 'unidad', 'tipo', 'obligatorio', 'opciones'];

    protected $casts = [
        'opciones' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
