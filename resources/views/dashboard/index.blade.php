@extends('layouts.app')

@section('content')
    <div class="space-y-8">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white sm:text-3xl">
                Dashboard
            </h1>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
                Resumen de tickets y acceso rápido a la cola de trabajo.
            </p>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div
                class="overflow-hidden rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 p-5 shadow-sm dark:shadow-none ring-1 ring-slate-900/5"
            >
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">
                    Total tickets
                </p>
                <p class="mt-2 text-3xl font-semibold tabular-nums text-slate-900 dark:text-white">
                    {{ $total ?? 0 }}
                </p>
            </div>
            <div
                class="overflow-hidden rounded-xl border border-amber-200/80 bg-gradient-to-br from-amber-50 to-white p-5 shadow-sm dark:shadow-none"
            >
                <p class="text-xs font-medium uppercase tracking-wide text-amber-800/80">
                    Abiertos
                </p>
                <p class="mt-2 text-3xl font-semibold tabular-nums text-amber-950">
                    {{ $abiertos ?? 0 }}
                </p>
            </div>
            <div
                class="overflow-hidden rounded-xl border border-sky-200/80 bg-gradient-to-br from-sky-50 to-white p-5 shadow-sm dark:shadow-none"
            >
                <p class="text-xs font-medium uppercase tracking-wide text-sky-800/80">
                    En proceso
                </p>
                <p class="mt-2 text-3xl font-semibold tabular-nums text-sky-950">
                    {{ $proceso ?? 0 }}
                </p>
            </div>
            <div
                class="overflow-hidden rounded-xl border border-emerald-200/80 bg-gradient-to-br from-emerald-50 to-white p-5 shadow-sm dark:shadow-none"
            >
                <p class="text-xs font-medium uppercase tracking-wide text-emerald-800/80">
                    Cerrados
                </p>
                <p class="mt-2 text-3xl font-semibold tabular-nums text-emerald-950">
                    {{ $cerrados ?? 0 }}
                </p>
            </div>
        </div>

        <div>
            <h2 class="text-base font-semibold text-slate-900 dark:text-white">Estadísticas</h2>
            <p class="mt-0.5 text-sm text-slate-500 dark:text-slate-400">
                @if (auth()->user()->isTecnico())
                    Métricas y gráficos solo de tus tickets asignados (independiente de los filtros de la tabla).
                @else
                    Vista global de todos los tickets (independiente de los filtros de la tabla).
                @endif
            </p>

            <div class="mt-5 grid gap-6 lg:grid-cols-2">
                <div
                    class="overflow-hidden rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 p-5 shadow-sm dark:shadow-none sm:p-6"
                >
                    <h3 class="text-sm font-semibold text-slate-800 dark:text-slate-200">Por estado</h3>
                    <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">Distribución actual según estado del ticket.</p>
                    <div class="relative mx-auto mt-4 h-64 max-w-xs sm:h-72">
                        <canvas id="chart-tickets-por-estado" aria-label="Gráfico de tickets por estado"></canvas>
                    </div>
                </div>
                <div
                    class="overflow-hidden rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 p-5 shadow-sm dark:shadow-none sm:p-6"
                >
                    <h3 class="text-sm font-semibold text-slate-800 dark:text-slate-200">Últimos 30 días</h3>
                    <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">Tickets creados por día.</p>
                    <div class="relative mt-4 h-64 sm:h-72">
                        <canvas id="chart-tickets-por-dia" aria-label="Gráfico de tickets creados por día"></canvas>
                    </div>
                </div>
            </div>

            <div
                class="mt-6 overflow-hidden rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 p-5 shadow-sm dark:shadow-none sm:p-6"
            >
                <h3 class="text-sm font-semibold text-slate-800 dark:text-slate-200">Por técnico</h3>
                <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">Cantidad de tickets asignados (incluye sin asignar).</p>
                <div class="relative mt-4 h-56 sm:h-64">
                    <canvas id="chart-tickets-por-tecnico" aria-label="Gráfico de tickets por técnico"></canvas>
                </div>
            </div>
        </div>

        <script id="dashboard-charts-payload" type="application/json">
            @json($chartData)
        </script>

        <div class="rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 p-5 shadow-sm dark:shadow-none sm:p-6">
            <h2 class="text-base font-semibold text-slate-900 dark:text-white">Filtros</h2>
            <p class="mt-0.5 text-sm text-slate-500 dark:text-slate-400">Refiná la lista sin salir del tablero.</p>

            <form method="GET" class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-12 lg:items-end">
                <div class="sm:col-span-1 lg:col-span-2">
                    <label for="ticket_id" class="block text-xs font-medium text-slate-600 dark:text-slate-400">ID ticket</label>
                    <input
                        id="ticket_id"
                        type="text"
                        name="ticket_id"
                        value="{{ request('ticket_id') }}"
                        placeholder="Ej. 12"
                        class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-500 text-sm shadow-sm dark:shadow-none focus:border-indigo-500 focus:ring-indigo-500"
                    />
                </div>
                <div class="sm:col-span-1 lg:col-span-4">
                    <label for="cliente_id" class="block text-xs font-medium text-slate-600 dark:text-slate-400">Cliente</label>
                    <select
                        id="cliente_id"
                        name="cliente_id"
                        class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-500 text-sm shadow-sm dark:shadow-none focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        <option value="">Todos</option>
                        @foreach ($clientes as $c)
                            <option value="{{ $c->id }}" @selected(request('cliente_id') == $c->id)>
                                {{ $c->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="sm:col-span-2 lg:col-span-4">
                    <label for="estado_id" class="block text-xs font-medium text-slate-600 dark:text-slate-400">Estado</label>
                    <select
                        id="estado_id"
                        name="estado_id"
                        class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-500 text-sm shadow-sm dark:shadow-none focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        <option value="">Todos</option>
                        @foreach ($estados as $e)
                            <option value="{{ $e->id }}" @selected(request('estado_id') == $e->id)>
                                {{ $e->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex flex-wrap gap-2 sm:col-span-2 lg:col-span-2">
                    <button
                        type="submit"
                        class="inline-flex flex-1 items-center justify-center rounded-lg bg-indigo-600 dark:bg-indigo-500 px-4 py-2 text-sm font-semibold text-white shadow-sm dark:shadow-none transition hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        Aplicar
                    </button>
                    <a
                        href="{{ route('dashboard') }}"
                        class="inline-flex flex-1 items-center justify-center rounded-lg border border-slate-300 dark:border-slate-500 bg-white dark:bg-slate-800 px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-300 shadow-sm dark:shadow-none transition hover:bg-slate-50 dark:hover:bg-slate-900"
                    >
                        Limpiar
                    </a>
                </div>
            </form>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 shadow-sm dark:shadow-none">
            <div class="border-b border-slate-100 dark:border-slate-700 px-5 py-4 sm:px-6">
                <h2 class="text-base font-semibold text-slate-900 dark:text-white">Tickets</h2>
                <p class="mt-0.5 text-sm text-slate-500 dark:text-slate-400">
                    {{ $tickets->count() }} {{ $tickets->count() === 1 ? 'resultado' : 'resultados' }}
                </p>
            </div>

            @if ($tickets->isEmpty())
                <div class="px-5 py-16 text-center sm:px-6">
                    <p class="text-sm font-medium text-slate-900 dark:text-white">No hay tickets con estos filtros</p>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        Probá otro criterio o
                        <a href="{{ route('dashboard') }}" class="font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-400">
                            restablecé los filtros
                        </a>.
                    </p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-600 text-left text-sm">
                        <thead class="bg-slate-50 dark:bg-slate-900">
                            <tr>
                                <th scope="col" class="px-5 py-3 font-semibold text-slate-700 dark:text-slate-300 sm:px-6">
                                    ID
                                </th>
                                <th scope="col" class="px-3 py-3 font-semibold text-slate-700 dark:text-slate-300">
                                    Cliente
                                </th>
                                <th scope="col" class="px-3 py-3 font-semibold text-slate-700 dark:text-slate-300">
                                    Estado
                                </th>
                                <th scope="col" class="hidden px-3 py-3 font-semibold text-slate-700 dark:text-slate-300 md:table-cell">
                                    Técnico
                                </th>
                                <th scope="col" class="hidden px-3 py-3 font-semibold text-slate-700 dark:text-slate-300 lg:table-cell">
                                    Fecha
                                </th>
                                <th scope="col" class="px-5 py-3 text-right font-semibold text-slate-700 dark:text-slate-300 sm:px-6">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700 bg-white dark:bg-slate-800">
                            @foreach ($tickets as $t)
                                <tr class="transition hover:bg-slate-50 dark:hover:bg-slate-900/80 cursor-pointer" onclick="window.location='{{ route('tickets.show', $t) }}'">
                                    <td class="whitespace-nowrap px-5 py-3 font-mono text-xs text-slate-600 dark:text-slate-400 sm:px-6">
                                        #{{ $t->id }}
                                    </td>
                                    <td class="px-3 py-3 font-medium text-slate-900 dark:text-white">
                                        {{ $t->cliente->nombre }}
                                    </td>
                                    <td class="px-3 py-3">
                                        <x-ticket-status :estado="$t->estadoActual" />
                                    </td>
                                    <td class="hidden whitespace-nowrap px-3 py-3 text-slate-600 dark:text-slate-400 md:table-cell">
                                        {{ $t->tecnico->nombre ?? '—' }}
                                    </td>
                                    <td class="hidden whitespace-nowrap px-3 py-3 text-slate-600 dark:text-slate-400 lg:table-cell">
                                        {{ $t->created_at?->timezone(config('app.timezone'))->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-3 text-right sm:px-6">
                                        <a
                                            href="{{ route('tickets.show', $t->id) }}"
                                            class="mr-1 inline-flex rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-2.5 py-1 text-xs font-semibold text-slate-700 dark:text-slate-300 shadow-sm dark:shadow-none hover:bg-slate-50 dark:hover:bg-slate-900"
                                        >
                                            Ver
                                        </a>
                                        @can('editBase', $t)
                                            <a
                                                href="{{ route('tickets.edit', $t->id) }}"
                                                class="inline-flex rounded-lg bg-indigo-600 dark:bg-indigo-500 px-2.5 py-1 text-xs font-semibold text-white shadow-sm dark:shadow-none hover:bg-indigo-500"
                                            >
                                                Editar
                                            </a>
                                        @endcan
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

@push('scripts')
    @vite(['resources/js/dashboard-charts.js'])
@endpush
