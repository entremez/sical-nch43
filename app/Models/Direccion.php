<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Direccion extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'IS_DIRECCIONES';

    public function region()
    {
        return $this->belongsTo('App\Models\Region', 'CodRegion', 'CODIGO');
    }

}
