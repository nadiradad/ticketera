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

        $query = Ticket::with(['cliente', 'equipo', 'estadoActual']);

        if ($uid) {
            $query->where('tecnico_id', $uid);
        } else {
            $query->whereRaw('0 = 1');
        }

        if ($request->filled('estado_id')) {
            $query->where('estado_actual_id', $request->estado_id);
        }

        $tickets = $query->orderByDesc('updated_at')->get();

        return view('mis-tickets.index', [
            'tickets' => $tickets,
            'estados' => Estado::query()->orderBy('id')->get(),
        ]);
    }
}
