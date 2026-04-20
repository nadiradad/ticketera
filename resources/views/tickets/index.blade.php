<h1>Tickets</h1>

<a href="{{ route('tickets.create') }}">Nuevo Ticket</a>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Cliente</th>
        <th>Estado</th>
        <th>Técnico</th>
        <th>Acciones</th>
    </tr>

    @foreach($tickets as $ticket)
    <tr>
        <td>{{ $ticket->id }}</td>
        <td>{{ $ticket->cliente->nombre }}</td>
        <td>{{ $ticket->estadoActual->nombre }}</td>
        <td>{{ $ticket->tecnico->nombre ?? '-' }}</td>
        <td>
            <a href="{{ route('tickets.show', $ticket->id) }}">Ver</a>
            <a href="{{ route('tickets.edit', $ticket->id) }}">Editar</a>

            <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button>Eliminar</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>