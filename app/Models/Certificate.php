<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $fillable = [
        'cliente',
        'contacto',
        'direccion',
        "cliente",
        "contacto",
        "direccion",
        "fabrica",
        "direccion_fabrica",
        "producto",
        "marca",
        "modelo",
        "certificado",
        "qr",
        "area",
        "active"
    ];

    public function process()
    {
        return $this->belongsTo(Process::class);
    }

    public function productModels()
    {
        return $this->belongsToMany(ProductModel::class, 'certificate_product_model');
    }



    public function productModel()
    {
        return $this->belongsTo(ProductModel::class);
    }

    // En Certificate.php
    public function getModelListAttribute()
    {
        return $this->productModels->pluck('name')->implode("\n");
    }
}
