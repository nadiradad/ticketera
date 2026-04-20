<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketRepuesto extends Model
{
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function repuesto()
    {
        return $this->belongsTo(Repuesto::class);
    }
}
