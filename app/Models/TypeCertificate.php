<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeCertificate extends Model
{
    public function technicalCharacteristics()
    {
        return $this->hasMany(TechnicalCharacteristic::class);
    }
}
