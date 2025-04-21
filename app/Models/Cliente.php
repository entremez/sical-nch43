<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'IS_CLIENTES';
}
