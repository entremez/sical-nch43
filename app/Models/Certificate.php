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
}
