<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Llamada extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'IS_LLAMADAS';
}
