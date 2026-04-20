<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    public function ticketsAsignados()
    {
        return $this->hasMany(Ticket::class, 'tecnico_id');
    }

    public function historial()
    {
        return $this->hasMany(EstadoHistorial::class);
    }
}
