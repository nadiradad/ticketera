<h1>Detalle Ticket</h1>

<p>Cliente: {{ $ticket->cliente->nombre }}</p>
<p>Equipo: {{ $ticket->equipo->marca }}</p>
<p>Estado: {{ $ticket->estadoActual->nombre }}</p>
<p>Descripción: {{ $ticket->descripcion }}</p>

<h3>Agregar Repuesto</h3>

<form method="POST" action="{{ route('tickets.repuestos.agregar', $ticket->id) }}">
@csrf

<select name="repuesto_id" class="form-control mb-2">
    @foreach(\App\Models\Repuesto::all() as $r)
        <option value="{{ $r->id }}">
            {{ $r->nombre }} ({{ $r->precio_base }})
        </option>
    @endforeach
</select>

<input type="number" name="cantidad" placeholder="Cantidad" class="form-control mb-2" required>

<input type="number" step="0.01" name="precio_unitario" placeholder="Precio" class="form-control mb-2" required>

<button class="btn btn-primary">Agregar</button>

</form>

<h3>Repuestos usados</h3>

<table class="table">
    <tr>
        <th>Nombre</th>
        <th>Cantidad</th>
        <th>Precio</th>
        <th>Subtotal</th>
    </tr>

    @foreach($ticket->repuestos as $r)
    <tr>
        <td>{{ $r->nombre }}</td>
        <td>{{ $r->pivot->cantidad }}</td>
        <td>{{ $r->pivot->precio_unitario }}</td>
        <td>{{ $r->pivot->cantidad * $r->pivot->precio_unitario }}</td>
    </tr>
    @endforeach
</table>

<h4>Total Servicio: ${{ $ticket->monto_servicio }}</h4>
<h4>Total Repuestos: ${{ $ticket->repuestos->sum(fn($r) => $r->pivot->cantidad * $r->pivot->precio_unitario) }}</h4>
<h3>Total Final: ${{ $ticket->total }}</h3>

<h3>Historial del Ticket</h3>

<p>
    👤 {{ $h->usuario->nombre ?? 'Sistema' }}
</p>

<div class="timeline">
    @foreach($ticket->historial as $h)
        <div class="timeline-item">
            <div class="timeline-dot"></div>

            <div class="timeline-content">
                <strong style="
                color:
                {{ $h->estado->nombre == 'Cerrado OK' ? 'green' :
                ($h->estado->nombre == 'Cerrado sin exito' ? 'red' : 'blue') }}
                ">
                    {{ $h->estado->nombre }}
                </strong><br>

                <small>
                    {{ \Carbon\Carbon::parse($h->fecha)->format('d/m/Y H:i') }}
                </small>

                <p>{{ $h->comentario }}</p>
            </div>
        </div>
    @endforeach
</div>

<style>
.timeline {
    position: relative;
    margin: 20px 0;
    padding-left: 30px;
    border-left: 3px solid #3490dc;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-dot {
    position: absolute;
    left: -10px;
    top: 5px;
    width: 15px;
    height: 15px;
    background-color: #3490dc;
    border-radius: 50%;
}

.timeline-content {
    background: #f8f9fa;
    padding: 10px;
    border-radius: 5px;
}
</style>