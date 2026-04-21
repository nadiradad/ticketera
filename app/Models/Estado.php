<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'nombre',
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'estado_actual_id');
    }
}
