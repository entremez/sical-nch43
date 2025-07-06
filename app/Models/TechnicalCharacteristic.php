<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TechnicalCharacteristic extends Model
{
    protected $fillable = [
        'type_certificate_id',
        'characteristic_template_id',
        'valor',
    ];

    // Relación con el certificado de tipo
    public function typeCertificate()
    {
        return $this->belongsTo(TypeCertificate::class);
    }

    // Relación con la plantilla
    public function template()
    {
        return $this->belongsTo(CharacteristicTemplate::class, 'characteristic_template_id');
    }
}

