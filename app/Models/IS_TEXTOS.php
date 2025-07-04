<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IS_TEXTOS extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'IS_TEXTOS';

    public function direccion()
    {
        return $this->hasOne(Direccion::class, 'IdDireccion', 'IdRelated');
    }
}
