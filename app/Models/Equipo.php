<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
