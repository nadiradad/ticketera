<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = [
        'nombre',
        'apellido',
        'dni',
        'telefono',
        'correo',
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
