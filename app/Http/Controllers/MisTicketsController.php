<?php

namespace App\Http\Controllers;

use App\Models\Estado;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MisTicketsController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $uid = $user->usuario?->id;

        $queryMisTickets = Ticket::with(['cliente', 'equipo', 'estadoActual']);
        $querySinAsignar = Ticket::with(['cliente', 'equipo', 'estadoActual']);

        if ($uid) {
            $queryMisTickets->where('tecnico_id', $uid);
            $querySinAsignar->whereNull('tecnico_id');
        } else {
            $queryMisTickets->whereRaw('0 = 1');
            $querySinAsignar->whereRaw('0 = 1');
        }

        if ($request->filled('estado_id')) {
            $queryMisTickets->where('estado_actual_id', $request->estado_id);
            $querySinAsignar->where('estado_actual_id', $request->estado_id);
        }

        $misTickets = $queryMisTickets->orderByDesc('updated_at')->get();
        $ticketsSinAsignar = $querySinAsignar->orderByDesc('updated_at')->get();

        return view('mis-tickets.index', [
            'misTickets' => $misTickets,
            'ticketsSinAsignar' => $ticketsSinAsignar,
            'estados' => Estado::query()->orderBy('id')->get(),
        ]);
    }
}
