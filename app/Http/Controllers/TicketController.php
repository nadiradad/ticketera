<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Equipo;
use App\Models\Estado;
use App\Models\EstadoHistorial;
use App\Models\Repuesto;
use App\Models\Ticket;
use App\Models\Usuario;
use App\Notifications\TicketAsignado;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TicketController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): View|RedirectResponse
    {
        if (Auth::user()->isTecnico()) {
            return redirect()->route('mis-tickets.index');
        }

        $this->authorize('viewAny', Ticket::class);

        $query = Ticket::with(['cliente', 'equipo', 'tecnico', 'estadoActual'])
            ->orderByDesc('updated_at');

        if ($request->filled('ticket_id')) {
            $query->where('id', $request->ticket_id);
        }

        $tickets = $query->get();

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

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => ['required'],
            'apellido' => ['required'],
            'dni' => ['required'],
            'telefono' => ['required'],

            'marca' => ['required'],
            'modelo' => ['required'],

            'descripcion' => ['required'],
        ]);

        // CREAR CLIENTE
        $cliente = Cliente::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'dni' => $request->dni,
            'telefono' => $request->telefono,
            'correo' => $request->correo,
        ]);

        // CREAR EQUIPO
        $equipo = Equipo::create([
            'cliente_id' => $cliente->id,
            'marca' => $request->marca,
            'modelo' => $request->modelo,
            'descripcion' => $request->descripcion_equipo,
        ]);

        // CREAR TICKET
        Ticket::create([
            'user_id' => auth()->id(),
            'cliente_id' => $cliente->id,
            'equipo_id' => $equipo->id,
            'tecnico_id' => null,
            'estado_actual_id' => 1,
            'descripcion' => $request->descripcion,
            'monto_servicio' => 0,
            'total' => 0,
        ]);

        return redirect()->route('tickets.index')
            ->with('success', 'Ticket creado correctamente');
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
        $estados = Estado::all();

        if (Auth::user()->isTecnico()) {
            $tecnicos = Usuario::where('id', Auth::user()->usuario?->id)->get();
        } else {
            $tecnicos = Usuario::where('rol', 'tecnico')->get();
        }

        return view('tickets.show', compact('ticket', 'repuestos', 'estados', 'tecnicos'));
    }

    public function downloadPdf(Ticket $ticket): Response
    {
        $this->authorize('view', $ticket);

        $ticket->load(['cliente', 'equipo', 'tecnico', 'estadoActual', 'repuestos']);

        $pdf = Pdf::loadView('tickets.pdf', [
            'ticket' => $ticket,
            'generatedAt' => now(),
        ]);

        return $pdf->download('ticket_'.$ticket->id.'.pdf');
    }

    public function edit(Ticket $ticket): View
    {
        $this->authorize('editBase', $ticket);

        $clientes = Cliente::all();
        $equipos = Equipo::all();
        $tecnicos = Usuario::where('rol', 'tecnico')->get();
        $estados = Estado::all();

        return view('tickets.edit', compact('ticket', 'clientes', 'equipos', 'tecnicos', 'estados'));
    }

    public function update(Request $request, Ticket $ticket): RedirectResponse
    {
        $this->authorize('editBase', $ticket);

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

        $oldTecnicoId = $ticket->tecnico_id;
        $ticket->update($data);

        if ($ticket->tecnico_id && $ticket->tecnico_id !== $oldTecnicoId && $ticket->tecnico->user) {
            $ticket->tecnico->user->notify(new TicketAsignado($ticket));
        }

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

        $repuesto = Repuesto::find($request->repuesto_id);

        // Descontar stock del repuesto
        $repuesto->decrement('stock', $request->cantidad);

        $ticket->repuestos()->syncWithoutDetaching([
            $request->repuesto_id => [
                'cantidad' => $request->cantidad,
                'precio_unitario' => $request->precio_unitario,
            ],
        ]);

        $this->recalcularTotal($ticket);

        return redirect()->back();
    }

    public function actualizarMontoServicio(Request $request, Ticket $ticket): RedirectResponse
    {
        $this->authorize('update', $ticket);

        $data = $request->validate([
            'monto_servicio' => ['required', 'numeric', 'min:0'],
        ]);

        $ticket->monto_servicio = (float) $data['monto_servicio'];
        $ticket->save();

        $this->recalcularTotal($ticket);

        return redirect()->back();
    }

    public function obtenerPrecioRepuesto(Repuesto $repuesto): JsonResponse
    {
        return response()->json([
            'precio_base' => $repuesto->precio_base,
            'stock' => $repuesto->stock,
        ]);
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

    public function agregarHistorial(Request $request, Ticket $ticket): RedirectResponse
    {
        $this->authorize('update', $ticket);

        $request->merge([
            'tecnico_id' => $request->filled('tecnico_id') ? $request->input('tecnico_id') : null,
        ]);

        if (Auth::user()->isTecnico() && $request->input('tecnico_id') && $request->input('tecnico_id') != Auth::user()->usuario?->id) {
            abort(403, 'No puedes asignar el ticket a otro técnico.');
        }

        $request->validate([
            'estado_id' => ['required', 'exists:estados,id'],
            'tecnico_id' => ['nullable', 'exists:usuarios,id'],
            'comentario' => ['nullable', 'string', 'max:1000'],
        ]);

        $oldTecnicoId = $ticket->tecnico_id;
        $newTecnicoId = $request->input('tecnico_id');
        $cambioEstado = $ticket->estado_actual_id != $request->estado_id;
        $cambioTecnico = $oldTecnicoId != $newTecnicoId;

        if (! $cambioEstado && ! $cambioTecnico && empty($request->comentario)) {
            return redirect()->back();
        }

        EstadoHistorial::create([
            'ticket_id' => $ticket->id,
            'estado_id' => $request->estado_id,
            'usuario_id' => Auth::user()->usuario?->id,
            'comentario' => $request->comentario,
            'fecha' => now(),
        ]);

        if ($cambioEstado || $cambioTecnico) {
            $ticket->update([
                'estado_actual_id' => $request->estado_id,
                'tecnico_id' => $newTecnicoId,
            ]);
        }

        if ($cambioTecnico && $ticket->tecnico_id && $ticket->tecnico->user) {
            $ticket->tecnico->user->notify(new TicketAsignado($ticket));
        }

        return redirect()->back();
    }
}
