<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Equipo;
use App\Models\Estado;
use App\Models\Repuesto;
use App\Models\Ticket;
use App\Models\Usuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TicketController extends Controller
{
    use AuthorizesRequests;
    public function index(): View|RedirectResponse
    {
        if (Auth::user()->isTecnico()) {
            return redirect()->route('mis-tickets.index');
        }

        $this->authorize('viewAny', Ticket::class);

        $tickets = Ticket::with(['cliente', 'equipo', 'tecnico', 'estadoActual'])
            ->orderByDesc('updated_at')
            ->get();

        return view('tickets.index', compact('tickets'));
    }

    public function create(): View
    {
        $this->authorize('create', Ticket::class);

        $clientes = Cliente::all();
        $equipos = Equipo::all();
        $tecnicos = Usuario::where('rol', 'tecnico')->get();
        $estados = Estado::all();

        return view('tickets.create', compact('clientes', 'equipos', 'tecnicos', 'estados'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Ticket::class);

        $request->merge([
            'tecnico_id' => $request->filled('tecnico_id') ? $request->input('tecnico_id') : null,
        ]);

        $data = $request->validate([
            'cliente_id' => ['required', 'exists:clientes,id'],
            'equipo_id' => ['required', 'exists:equipos,id'],
            'tecnico_id' => ['nullable', 'exists:usuarios,id'],
            'estado_actual_id' => ['required', 'exists:estados,id'],
            'descripcion' => ['required', 'string'],
        ]);

        $ticket = Ticket::create([
            ...$data,
            'monto_servicio' => 0,
            'total' => 0,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('tickets.show', $ticket);
    }

    public function show(Ticket $ticket): View
    {
        $this->authorize('view', $ticket);

        $ticket->load([
            'cliente',
            'equipo',
            'tecnico',
            'estadoActual',
            'historial.estado',
            'historial.usuario',
            'repuestos',
        ]);

        $repuestos = Repuesto::query()->orderBy('nombre')->get();

        return view('tickets.show', compact('ticket', 'repuestos'));
    }

    public function edit(Ticket $ticket): View
    {
        $this->authorize('update', $ticket);

        $clientes = Cliente::all();
        $equipos = Equipo::all();
        $tecnicos = Usuario::where('rol', 'tecnico')->get();
        $estados = Estado::all();

        return view('tickets.edit', compact('ticket', 'clientes', 'equipos', 'tecnicos', 'estados'));
    }

    public function update(Request $request, Ticket $ticket): RedirectResponse
    {
        $this->authorize('update', $ticket);

        $request->merge([
            'tecnico_id' => $request->filled('tecnico_id') ? $request->input('tecnico_id') : null,
        ]);

        $data = $request->validate([
            'cliente_id' => ['required', 'exists:clientes,id'],
            'equipo_id' => ['required', 'exists:equipos,id'],
            'tecnico_id' => ['nullable', 'exists:usuarios,id'],
            'estado_actual_id' => ['required', 'exists:estados,id'],
            'descripcion' => ['required', 'string'],
        ]);

        $ticket->update($data);

        return redirect()->route('tickets.show', $ticket);
    }

    public function destroy(Ticket $ticket): RedirectResponse
    {
        $this->authorize('delete', $ticket);

        $ticket->delete();

        return redirect()->route('tickets.index');
    }

    public function agregarRepuesto(Request $request, Ticket $ticket): RedirectResponse
    {
        
        $this->authorize('update', $ticket);

        $request->validate([
            'repuesto_id' => ['required', 'exists:repuestos,id'],
            'cantidad' => ['required', 'integer', 'min:1'],
            'precio_unitario' => ['required', 'numeric', 'min:0'],
        ]);

        $ticket->repuestos()->syncWithoutDetaching([
            $request->repuesto_id => [
                'cantidad' => $request->cantidad,
                'precio_unitario' => $request->precio_unitario,
            ],
        ]);

        $this->recalcularTotal($ticket);

        return redirect()->back();
    }

    private function recalcularTotal(Ticket $ticket): void
    {
        $totalRepuestos = 0;

        foreach ($ticket->repuestos as $r) {
            $totalRepuestos += $r->pivot->cantidad * $r->pivot->precio_unitario;
        }

        $ticket->total = $ticket->monto_servicio + $totalRepuestos;
        $ticket->save();
    }
}
