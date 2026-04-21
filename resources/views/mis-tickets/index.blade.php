@extends('layouts.app')

@section('content')
    <div class="space-y-8">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Mis tickets</h1>
            <p class="mt-1 text-sm text-slate-600">
                Trabajá la cola asignada a vos: actualizá estados, descripción y repuestos desde cada ticket.
            </p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <form method="GET" class="flex flex-wrap items-end gap-4">
                <div class="min-w-[12rem]">
                    <label for="estado_id" class="block text-xs font-medium text-slate-600">Estado</label>
                    <select
                        id="estado_id"
                        name="estado_id"
                        class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        onchange="this.form.submit()"
                    >
                        <option value="">Todos</option>
                        @foreach ($estados as $e)
                            <option value="{{ $e->id }}" @selected(request('estado_id') == $e->id)>
                                {{ $e->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <a
                    href="{{ route('mis-tickets.index') }}"
                    class="inline-flex rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50"
                >
                    Limpiar filtro
                </a>
            </form>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            @if ($tickets->isEmpty())
                <div class="px-5 py-16 text-center sm:px-6">
                    <p class="text-sm font-medium text-slate-900">No tenés tickets asignados</p>
                    <p class="mt-1 text-sm text-slate-500">
                        Cuando recepción te asigne trabajo, aparecerán acá.
                    </p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-5 py-3 font-semibold text-slate-700 sm:px-6">ID</th>
                                <th class="px-3 py-3 font-semibold text-slate-700">Cliente</th>
                                <th class="px-3 py-3 font-semibold text-slate-700">Equipo</th>
                                <th class="px-3 py-3 font-semibold text-slate-700">Estado</th>
                                <th class="px-5 py-3 text-right font-semibold text-slate-700 sm:px-6">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach ($tickets as $ticket)
                                <tr class="transition hover:bg-slate-50/80">
                                    <td class="whitespace-nowrap px-5 py-3 font-mono text-xs text-slate-600 sm:px-6">
                                        #{{ $ticket->id }}
                                    </td>
                                    <td class="px-3 py-3 font-medium text-slate-900">
                                        {{ $ticket->cliente->nombre }}
                                    </td>
                                    <td class="px-3 py-3 text-slate-600">
                                        {{ $ticket->equipo->marca }} {{ $ticket->equipo->modelo }}
                                    </td>
                                    <td class="px-3 py-3">
                                        <span
                                            class="inline-flex rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-800"
                                        >
                                            {{ $ticket->estadoActual->nombre }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-3 text-right sm:px-6">
                                        <a
                                            href="{{ route('tickets.show', $ticket) }}"
                                            class="inline-flex rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-indigo-500"
                                        >
                                            Abrir ticket
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
