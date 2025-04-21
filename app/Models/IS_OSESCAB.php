<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IS_OSESCAB extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'IS_OSESCAB';

    public function cliente()
    {
        return $this->hasOne(Cliente::class, 'IdCliente', 'IdCliente');
    }

    public function llamada()
    {
        return $this->hasOne(Llamada::class, 'IdOse', 'IdOse');
    }

    public function solicitud()
    {
        return $this->hasMany(Solicitud::class, 'IdSolicitud', 'IdSolicitud');
    }

    public function marcaModelo()
    {
        return $this->hasMany(MarcaModelo::class, 'IdSolicitud', 'IdSolicitud');
    }

    public function contacto()
    {
        return $this->hasOne(Contacto::class, 'IdContacto', 'IdContacto');
    }

    public function protocolo()
    {
        return $this->hasOne(Protocolo::class, 'IdProtocolo', 'IdProtocolo');
    }
}
