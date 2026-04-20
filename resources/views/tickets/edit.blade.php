<h1>Editar Ticket</h1>

<form method="POST" action="{{ route('tickets.update', $ticket->id) }}">
@csrf
@method('PUT')

<select name="cliente_id">
    @foreach($clientes as $c)
        <option value="{{ $c->id }}" 
            {{ $ticket->cliente_id == $c->id ? 'selected' : '' }}>
            {{ $c->nombre }}
        </option>
    @endforeach
</select>

<select name="equipo_id">
    @foreach($equipos as $e)
        <option value="{{ $e->id }}" 
            {{ $ticket->equipo_id == $e->id ? 'selected' : '' }}>
            {{ $e->marca }} {{ $e->modelo }}
        </option>
    @endforeach
</select>

<select name="tecnico_id">
    <option value="">Sin asignar</option>
    @foreach($tecnicos as $t)
        <option value="{{ $t->id }}" 
            {{ $ticket->tecnico_id == $t->id ? 'selected' : '' }}>
            {{ $t->nombre }}
        </option>
    @endforeach
</select>

<select name="estado_actual_id">
    @foreach($estados as $e)
        <option value="{{ $e->id }}" 
            {{ $ticket->estado_actual_id == $e->id ? 'selected' : '' }}>
            {{ $e->nombre }}
        </option>
    @endforeach
</select>

<textarea name="descripcion">{{ $ticket->descripcion }}</textarea>

<button type="submit">Actualizar</button>
</form>