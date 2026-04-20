<h1>Crear Ticket</h1>

<form method="POST" action="{{ route('tickets.store') }}">
@csrf

<select name="cliente_id">
    @foreach($clientes as $c)
        <option value="{{ $c->id }}">{{ $c->nombre }}</option>
    @endforeach
</select>

<select name="equipo_id">
    @foreach($equipos as $e)
        <option value="{{ $e->id }}">{{ $e->marca }} {{ $e->modelo }}</option>
    @endforeach
</select>

<select name="tecnico_id">
    <option value="">Sin asignar</option>
    @foreach($tecnicos as $t)
        <option value="{{ $t->id }}">{{ $t->nombre }}</option>
    @endforeach
</select>

<select name="estado_actual_id">
    @foreach($estados as $e)
        <option value="{{ $e->id }}">{{ $e->nombre }}</option>
    @endforeach
</select>

<textarea name="descripcion"></textarea>

<button type="submit">Guardar</button>
</form>