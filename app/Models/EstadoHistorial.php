<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadoHistorial extends Model
{
    protected $table = 'estados_historial';

    protected $fillable = [
        'ticket_id',
        'estado_id',
        'usuario_id',
        'comentario',
        'fecha',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
}
