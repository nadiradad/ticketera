<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Usuario extends Model
{
    protected $fillable = [
        'user_id',
        'nombre',
        'email',
        'password',
        'rol',
    ];

    protected $hidden = [
        'password',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function ticketsAsignados()
    {
        return $this->hasMany(Ticket::class, 'tecnico_id');
    }

    public function historial()
    {
        return $this->hasMany(EstadoHistorial::class);
    }
}
