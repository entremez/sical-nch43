<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contacto extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'IS_CONTACTOS';
}
