<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Cliente;
use App\Models\Estado;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with(['cliente', 'tecnico', 'estadoActual']);

        // 🔍 FILTRO POR ID
        if ($request->filled('ticket_id')) {
            $query->where('id', $request->ticket_id);
        }

        // 🔍 FILTRO POR CLIENTE
        if ($request->filled('cliente_id')) {
            $query->where('cliente_id', $request->cliente_id);
        }

        // 🔍 FILTRO POR ESTADO
        if ($request->filled('estado_id')) {
            $query->where('estado_actual_id', $request->estado_id);
        }

        $tickets = $query->get();

        return view('dashboard.index', [
            'tickets' => $tickets,
            'clientes' => Cliente::all(),
            'estados' => Estado::all(),

            // KPIs
            'total' => Ticket::count(),
            'abiertos' => Ticket::where('estado_actual_id', 1)->count(),
            'proceso' => Ticket::where('estado_actual_id', 3)->count(),
            'cerrados' => Ticket::whereIn('estado_actual_id', [4,5])->count(),
        ]);
    }
}
