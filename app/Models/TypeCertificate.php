<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeCertificate extends Model
{
    protected $fillable = [
        'scope_id',
        'solicitud',
        'fecha_solicitud',
        'certificate',
        'certificate_date',
        'n_sec',
        'cliente_id',
        'fabrica_id',
        'test_report_number',
        'test_report_date',
        'laboratorio_id',
        'area',
        'denominacion_comercial',
        'protocolo_id'
    ];

    public function characteristics()
    {
        return $this->hasMany(TechnicalCharacteristic::class);
    }

    public function productModels()
    {
        return $this->belongsToMany(ProductModel::class, 'type_certificate_product_model');
    }
}
