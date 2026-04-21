<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function equipo()
    {
        return $this->belongsTo(Equipo::class);
    }

    public function tecnico()
    {
        return $this->belongsTo(Usuario::class, 'tecnico_id');
    }

    public function estadoActual()
    {
        return $this->belongsTo(Estado::class, 'estado_actual_id');
    }

    public function historial()
    {
        return $this->hasMany(EstadoHistorial::class)->orderBy('fecha', 'asc');
    }

    public function repuestos()
    {
        return $this->belongsToMany(Repuesto::class, 'ticket_repuestos')
            ->withPivot('cantidad', 'precio_unitario')
            ->withTimestamps();
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected $fillable = [
        'user_id',
        'cliente_id',
        'equipo_id',
        'tecnico_id',
        'estado_actual_id',
        'descripcion',
        'monto_servicio',
        'total',
    ];
}
