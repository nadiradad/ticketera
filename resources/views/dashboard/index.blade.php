@extends('layouts.app')

@section('content')

<h1>Dashboard</h1>

<div class="row">

    <div class="col-md-3">
        <div class="card bg-primary text-white p-3">
            Total Tickets: {{ $total ?? 0 }}
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-warning text-white p-3">
            Abiertos: {{ $abiertos ?? 0 }}
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-info text-white p-3">
            En proceso: {{ $proceso ?? 0 }}
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-success text-white p-3">
            Cerrados: {{ $cerrados ?? 0 }}
        </div>
    </div>

</div>

<form method="GET" class="row mb-4">

    <div class="col-md-2">
        <input type="text" name="ticket_id" class="form-control" placeholder="ID Ticket"
            value="{{ request('ticket_id') }}">
    </div>

    <div class="col-md-3">
        <select name="cliente_id" class="form-control">
            <option value="">Cliente</option>
            @foreach($clientes as $c)
                <option value="{{ $c->id }}" 
                    {{ request('cliente_id') == $c->id ? 'selected' : '' }}>
                    {{ $c->nombre }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-3">
        <select name="estado_id" class="form-control">
            <option value="">Estado</option>
            @foreach($estados as $e)
                <option value="{{ $e->id }}"
                    {{ request('estado_id') == $e->id ? 'selected' : '' }}>
                    {{ $e->nombre }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-2">
        <button class="btn btn-primary">Filtrar</button>
    </div>

    <div class="col-md-2">
        <a href="/" class="btn btn-secondary">Limpiar</a>
    </div>

</form>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Cliente</th>
            <th>Estado</th>
            <th>Técnico</th>
            <th>Fecha</th>
            <th>Acciones</th>
        </tr>
    </thead>

    <tbody>
        @foreach($tickets as $t)
        <tr>
            <td>{{ $t->id }}</td>
            <td>{{ $t->cliente->nombre }}</td>
            <td>{{ $t->estadoActual->nombre }}</td>
            <td>{{ $t->tecnico->nombre ?? '-' }}</td>
            <td>{{ $t->created_at }}</td>
            <td>
                <a href="{{ route('tickets.show', $t->id) }}" class="btn btn-sm btn-info">Ver</a>
                <a href="{{ route('tickets.edit', $t->id) }}" class="btn btn-sm btn-warning">Editar</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection