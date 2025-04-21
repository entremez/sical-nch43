<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'IS_SOLIDETALLE';
}
