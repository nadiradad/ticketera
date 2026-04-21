<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Ticket $ticket): bool
    {
        if ($user->isAdministrador() || $user->isRecepcionista()) {
            return true;
        }

        if (! $user->isTecnico()) {
            return false;
        }

        $uid = $user->usuario?->id;

        return $uid !== null && (int) $ticket->tecnico_id === (int) $uid;
    }

    public function create(User $user): bool
    {
        return $user->isAdministrador() || $user->isRecepcionista();
    }

    public function update(User $user, Ticket $ticket): bool
    {
        if ($user->isAdministrador() || $user->isRecepcionista()) {
            return true;
        }

        if (! $user->isTecnico()) {
            return false;
        }

        $uid = $user->usuario?->id;

        return $uid !== null && (int) $ticket->tecnico_id === (int) $uid;
    }

    public function delete(User $user, Ticket $ticket): bool
    {
        return $user->isAdministrador();
    }
}
