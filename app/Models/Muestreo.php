<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Muestreo extends Model
{
    protected $fillable = [
       'id_proceso',
       'marca',
       'modelo',
       'lote',
       'muestras',
       'denominacion',
       'fecha',
       'sicalito',
       'year',
       'tipo_trazabilidad',
       'muestreo',
       'comentarios',
       'fila',
       'columna',
       'cliente',
       'contacto',
       'direccion',
       'sistema',
    ];
}
