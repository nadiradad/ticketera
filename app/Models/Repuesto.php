<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Repuesto extends Model
{
    public function tickets()
    {
        return $this->belongsToMany(Ticket::class, 'ticket_repuestos')
            ->withPivot('cantidad', 'precio_unitario')
            ->withTimestamps();
    }
}
