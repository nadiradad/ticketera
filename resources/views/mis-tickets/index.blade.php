@extends('layouts.app')

@section('content')
    <div class="space-y-8">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white sm:text-3xl">Mis tickets</h1>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
                Trabajá la cola asignada a vos o tomá nuevos tickets sin asignar.
            </p>
        </div>

        <div class="rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 p-5 shadow-sm dark:shadow-none sm:p-6">
            <form method="GET" class="flex flex-wrap items-end gap-4">
                <div class="min-w-[12rem]">
                    <label for="estado_id" class="block text-xs font-medium text-slate-600 dark:text-slate-400">Estado</label>
                    <select
                        id="estado_id"
                        name="estado_id"
                        class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-500 text-sm shadow-sm dark:shadow-none focus:border-indigo-500 focus:ring-indigo-500"
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
                    class="inline-flex rounded-lg border border-slate-300 dark:border-slate-500 bg-white dark:bg-slate-800 px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-300 shadow-sm dark:shadow-none hover:bg-slate-50 dark:hover:bg-slate-900"
                >
                    Limpiar filtro
                </a>
            </form>
        </div>

        <!-- Section 1: Sin asignar -->
        <div>
            <h2 class="text-lg font-bold tracking-tight text-slate-900 dark:text-white mb-4">Tickets Disponibles (Sin asignar)</h2>
            <div class="overflow-hidden rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 shadow-sm dark:shadow-none">
                @if ($ticketsSinAsignar->isEmpty())
                    <div class="px-5 py-10 text-center sm:px-6">
                        <p class="text-sm font-medium text-slate-900 dark:text-white">No hay tickets disponibles</p>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                            Todos los tickets actuales ya están asignados.
                        </p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-600 text-left text-sm">
                            <thead class="bg-slate-50 dark:bg-slate-900">
                                <tr>
                                    <th class="px-5 py-3 font-semibold text-slate-700 dark:text-slate-300 sm:px-6">ID</th>
                                    <th class="px-3 py-3 font-semibold text-slate-700 dark:text-slate-300">Cliente</th>
                                    <th class="px-3 py-3 font-semibold text-slate-700 dark:text-slate-300">Equipo</th>
                                    <th class="px-3 py-3 font-semibold text-slate-700 dark:text-slate-300">Estado</th>
                                    <th class="px-5 py-3 text-right font-semibold text-slate-700 dark:text-slate-300 sm:px-6">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700 bg-white dark:bg-slate-800">
                                @foreach ($ticketsSinAsignar as $ticket)
                                    <tr class="transition hover:bg-slate-50 dark:hover:bg-slate-900/80 cursor-pointer" onclick="window.location='{{ route('tickets.show', $ticket) }}'">
                                        <td class="whitespace-nowrap px-5 py-3 font-mono text-xs text-slate-600 dark:text-slate-400 sm:px-6">
                                            #{{ $ticket->id }}
                                        </td>
                                        <td class="px-3 py-3 font-medium text-slate-900 dark:text-white">
                                            {{ $ticket->cliente->nombre }}
                                        </td>
                                        <td class="px-3 py-3 text-slate-600 dark:text-slate-400">
                                            {{ $ticket->equipo->marca }} {{ $ticket->equipo->modelo }}
                                        </td>
                                        <td class="px-3 py-3">
                                            <x-ticket-status :estado="$ticket->estadoActual" />
                                        </td>
                                        <td class="whitespace-nowrap px-5 py-3 text-right sm:px-6">
                                            <span class="inline-flex rounded-lg bg-indigo-600 dark:bg-indigo-500 px-3 py-1.5 text-xs font-semibold text-white shadow-sm dark:shadow-none hover:bg-indigo-500">
                                                Tomar ticket
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <!-- Section 2: Mis Tickets -->
        <div>
            <h2 class="text-lg font-bold tracking-tight text-slate-900 dark:text-white mb-4">Mis Tickets Asignados</h2>
            <div class="overflow-hidden rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 shadow-sm dark:shadow-none">
                @if ($misTickets->isEmpty())
                    <div class="px-5 py-10 text-center sm:px-6">
                        <p class="text-sm font-medium text-slate-900 dark:text-white">No tenés tickets asignados</p>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                            Tomá un ticket disponible o esperá a que recepción te asigne uno.
                        </p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-600 text-left text-sm">
                            <thead class="bg-slate-50 dark:bg-slate-900">
                                <tr>
                                    <th class="px-5 py-3 font-semibold text-slate-700 dark:text-slate-300 sm:px-6">ID</th>
                                    <th class="px-3 py-3 font-semibold text-slate-700 dark:text-slate-300">Cliente</th>
                                    <th class="px-3 py-3 font-semibold text-slate-700 dark:text-slate-300">Equipo</th>
                                    <th class="px-3 py-3 font-semibold text-slate-700 dark:text-slate-300">Estado</th>
                                    <th class="px-5 py-3 text-right font-semibold text-slate-700 dark:text-slate-300 sm:px-6">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700 bg-white dark:bg-slate-800">
                                @foreach ($misTickets as $ticket)
                                    <tr class="transition hover:bg-slate-50 dark:hover:bg-slate-900/80 cursor-pointer" onclick="window.location='{{ route('tickets.show', $ticket) }}'">
                                        <td class="whitespace-nowrap px-5 py-3 font-mono text-xs text-slate-600 dark:text-slate-400 sm:px-6">
                                            #{{ $ticket->id }}
                                        </td>
                                        <td class="px-3 py-3 font-medium text-slate-900 dark:text-white">
                                            {{ $ticket->cliente->nombre }}
                                        </td>
                                        <td class="px-3 py-3 text-slate-600 dark:text-slate-400">
                                            {{ $ticket->equipo->marca }} {{ $ticket->equipo->modelo }}
                                        </td>
                                        <td class="px-3 py-3">
                                            <x-ticket-status :estado="$ticket->estadoActual" />
                                        </td>
                                        <td class="whitespace-nowrap px-5 py-3 text-right sm:px-6">
                                            <span class="inline-flex rounded-lg bg-indigo-600 dark:bg-indigo-500 px-3 py-1.5 text-xs font-semibold text-white shadow-sm dark:shadow-none hover:bg-indigo-500">
                                                Abrir ticket
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
