<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laboratory extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'IS_LABORATORIOS';
}
