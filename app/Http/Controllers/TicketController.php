<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Cliente;
use App\Models\Equipo;
use App\Models\Usuario;
use App\Models\Estado;
use App\Models\Repuesto;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with(['cliente', 'equipo', 'tecnico', 'estadoActual'])->get();
        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        $clientes = Cliente::all();
        $equipos = Equipo::all();
        $tecnicos = Usuario::where('rol', 'tecnico')->get();
        $estados = Estado::all();

        return view('tickets.create', compact('clientes', 'equipos', 'tecnicos', 'estados'));
    }

    public function store(Request $request)
    {
        $ticket = Ticket::create([
            'cliente_id' => $request->cliente_id,
            'equipo_id' => $request->equipo_id,
            'tecnico_id' => $request->tecnico_id,
            'estado_actual_id' => $request->estado_actual_id,
            'descripcion' => $request->descripcion,
            'monto_servicio' => 0,
            'total' => 0,
            'user_id' => Auth::id()
        ]);

        return redirect()->route('tickets.index');
    }

    public function show($id)
    {
        $ticket = Ticket::with(['cliente', 'equipo', 'tecnico', 'estadoActual', 'historial'])->findOrFail($id);
        return view('tickets.show', compact('ticket'));
    }

    public function edit($id)
    {
        $ticket = Ticket::findOrFail($id);
        $clientes = Cliente::all();
        $equipos = Equipo::all();
        $tecnicos = Usuario::where('rol', 'tecnico')->get();
        $estados = Estado::all();

        return view('tickets.edit', compact('ticket', 'clientes', 'equipos', 'tecnicos', 'estados'));
    }

    public function update(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        $ticket->update([
            'cliente_id' => $request->cliente_id,
            'equipo_id' => $request->equipo_id,
            'tecnico_id' => $request->tecnico_id,
            'estado_actual_id' => $request->estado_actual_id,
            'descripcion' => $request->descripcion
        ]);

        return redirect()->route('tickets.index');
    }

    public function destroy($id)
    {
        Ticket::delete($id);
        return redirect()->route('tickets.index');
    }

    public function agregarRepuesto(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        $ticket->repuestos()->syncWithoutDetaching([
            $request->repuesto_id => [
                'cantidad' => $request->cantidad,
                'precio_unitario' => $request->precio_unitario
            ]
        ]);

        $this->recalcularTotal($ticket);

        return redirect()->back();
    }

    private function recalcularTotal($ticket)
    {
        $totalRepuestos = 0;

        foreach ($ticket->repuestos as $r) {
            $totalRepuestos += $r->pivot->cantidad * $r->pivot->precio_unitario;
        }

        $ticket->total = $ticket->monto_servicio + $totalRepuestos;
        $ticket->save();
    }
}
