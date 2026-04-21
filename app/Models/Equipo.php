<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    protected $fillable = [
        'cliente_id',
        'marca',
        'modelo',
        'descripcion',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
