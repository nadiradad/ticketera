@extends('layouts.app')

@section('content')
    @php
        $totalRepuestos = $ticket->repuestos->sum(
            fn ($r) => $r->pivot->cantidad * $r->pivot->precio_unitario,
        );
    @endphp

    <div class="space-y-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <a
                    href="{{ auth()->user()->isTecnico() ? route('mis-tickets.index') : route('tickets.index') }}"
                    class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-400"
                >
                    ← {{ auth()->user()->isTecnico() ? 'Volver a mis tickets' : 'Volver al listado' }}
                </a>
                <h1 class="mt-4 text-2xl font-bold tracking-tight text-slate-900 dark:text-white sm:text-3xl">
                    Ticket #{{ $ticket->id }}
                </h1>
                <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
                    Creado
                    {{ $ticket->created_at?->timezone(config('app.timezone'))->format('d/m/Y H:i') }}
                </p>
            </div>
            @can('update', $ticket)
                <div class="flex flex-wrap gap-2">
                    <a
                        href="{{ route('tickets.edit', $ticket) }}"
                        class="inline-flex rounded-lg bg-indigo-600 dark:bg-indigo-500 px-4 py-2 text-sm font-semibold text-white shadow-sm dark:shadow-none hover:bg-indigo-500"
                    >
                        Editar
                    </a>
                </div>
            @endcan
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">
                <dl
                    class="grid gap-4 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 p-6 shadow-sm dark:shadow-none sm:grid-cols-2"
                >
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">Cliente</dt>
                        <dd class="mt-1 text-sm font-semibold text-slate-900 dark:text-white">{{ $ticket->cliente->nombre }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">Equipo</dt>
                        <dd class="mt-1 text-sm font-semibold text-slate-900 dark:text-white">
                            {{ $ticket->equipo->marca }}
                            {{ $ticket->equipo->modelo ?? '' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">Estado</dt>
                        <dd class="mt-1">
                            <x-ticket-status :estado="$ticket->estadoActual" />
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">Técnico</dt>
                        <dd class="mt-1 text-sm font-semibold text-slate-900 dark:text-white">
                            {{ $ticket->tecnico->nombre ?? 'Sin asignar' }}
                        </dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">Descripción</dt>
                        <dd class="mt-1 text-sm leading-relaxed text-slate-700 dark:text-slate-300 whitespace-pre-wrap">
                            {{ $ticket->descripcion ?: '—' }}
                        </dd>
                    </div>
                </dl>

                @can('update', $ticket)
                    <div class="rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 p-6 shadow-sm dark:shadow-none">
                        <h2 class="text-base font-semibold text-slate-900 dark:text-white">Agregar repuesto</h2>
                        <p class="mt-0.5 text-sm text-slate-500 dark:text-slate-400">Registrá piezas usados en este ticket.</p>

                        <form
                            method="POST"
                            action="{{ route('tickets.repuestos.agregar', $ticket) }}"
                            class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-12 lg:items-end"
                        >
                            @csrf
                            <div class="sm:col-span-2 lg:col-span-5">
                                <label for="repuesto_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Repuesto</label>
                                <select
                                    id="repuesto_id"
                                    name="repuesto_id"
                                    required
                                    class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-500 text-sm shadow-sm dark:shadow-none focus:border-indigo-500 focus:ring-indigo-500"
                                >
                                    <option value="" disabled selected>Seleccionar…</option>
                                    @foreach ($repuestos as $r)
                                        <option value="{{ $r->id }}">
                                            {{ $r->nombre }} (${{ number_format($r->precio_base, 2, ',', '.') }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="lg:col-span-3">
                                <label for="cantidad" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Cantidad</label>
                                <input
                                    id="cantidad"
                                    type="number"
                                    name="cantidad"
                                    min="1"
                                    required
                                    class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-500 text-sm shadow-sm dark:shadow-none focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="1"
                                />
                            </div>
                            <div class="lg:col-span-3">
                                <label for="precio_unitario" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                                    Precio unitario
                                </label>
                                <input
                                    id="precio_unitario"
                                    type="number"
                                    step="0.01"
                                    name="precio_unitario"
                                    required
                                    class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-500 text-sm shadow-sm dark:shadow-none focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="0.00"
                                />
                            </div>
                            <div class="sm:col-span-2 lg:col-span-1">
                                <button
                                    type="submit"
                                    class="w-full rounded-lg bg-indigo-600 dark:bg-indigo-500 px-4 py-2 text-sm font-semibold text-white shadow-sm dark:shadow-none hover:bg-indigo-500 sm:w-auto"
                                >
                                    Agregar
                                </button>
                            </div>
                        </form>
                    </div>
                @endcan

                <div class="overflow-hidden rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 shadow-sm dark:shadow-none">
                    <div class="border-b border-slate-100 dark:border-slate-700 px-5 py-4 sm:px-6">
                        <h2 class="text-base font-semibold text-slate-900 dark:text-white">Repuestos usados</h2>
                    </div>
                    @if ($ticket->repuestos->isEmpty())
                        <p class="px-5 py-10 text-center text-sm text-slate-500 dark:text-slate-400 sm:px-6">No hay repuestos cargados.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-600 text-left text-sm">
                                <thead class="bg-slate-50 dark:bg-slate-900">
                                    <tr>
                                        <th class="px-5 py-3 font-semibold text-slate-700 dark:text-slate-300 sm:px-6">Nombre</th>
                                        <th class="px-3 py-3 font-semibold text-slate-700 dark:text-slate-300">Cantidad</th>
                                        <th class="px-3 py-3 font-semibold text-slate-700 dark:text-slate-300">Precio u.</th>
                                        <th class="px-5 py-3 text-right font-semibold text-slate-700 dark:text-slate-300 sm:px-6">
                                            Subtotal
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                    @foreach ($ticket->repuestos as $r)
                                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/80">
                                            <td class="px-5 py-3 font-medium text-slate-900 dark:text-white sm:px-6">
                                                {{ $r->nombre }}
                                            </td>
                                            <td class="px-3 py-3 tabular-nums text-slate-600 dark:text-slate-400">
                                                {{ $r->pivot->cantidad }}
                                            </td>
                                            <td class="px-3 py-3 tabular-nums text-slate-600 dark:text-slate-400">
                                                ${{ number_format($r->pivot->precio_unitario, 2, ',', '.') }}
                                            </td>
                                            <td class="px-5 py-3 text-right font-medium tabular-nums text-slate-900 dark:text-white sm:px-6">
                                                ${{ number_format($r->pivot->cantidad * $r->pivot->precio_unitario, 2, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <div class="rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 p-6">
                    <h2 class="text-base font-semibold text-slate-900 dark:text-white">Totales</h2>
                    <dl class="mt-4 space-y-2 text-sm">
                        <div class="flex justify-between gap-4">
                            <dt class="text-slate-600 dark:text-slate-400">Servicio</dt>
                            <dd class="font-semibold tabular-nums text-slate-900 dark:text-white">
                                ${{ number_format($ticket->monto_servicio ?? 0, 2, ',', '.') }}
                            </dd>
                        </div>
                        <div class="flex justify-between gap-4">
                            <dt class="text-slate-600 dark:text-slate-400">Repuestos</dt>
                            <dd class="font-semibold tabular-nums text-slate-900 dark:text-white">
                                ${{ number_format($totalRepuestos, 2, ',', '.') }}
                            </dd>
                        </div>
                        <div
                            class="flex justify-between gap-4 border-t border-slate-200 dark:border-slate-600 pt-2 text-base font-bold text-slate-900 dark:text-white"
                        >
                            <dt>Total</dt>
                            <dd class="tabular-nums">${{ number_format($ticket->total ?? 0, 2, ',', '.') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 p-6 shadow-sm dark:shadow-none">
                    <h2 class="text-base font-semibold text-slate-900 dark:text-white">Historial</h2>
                    <p class="mt-0.5 text-sm text-slate-500 dark:text-slate-400">Cambios de estado y comentarios.</p>

                    @can('update', $ticket)
                        <form method="POST" action="{{ route('tickets.historial.agregar', $ticket) }}" class="mt-6 space-y-4">
                            @csrf
                            <div>
                                <label for="estado_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Estado</label>
                                <select id="estado_id" name="estado_id" required class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-500 text-sm shadow-sm dark:shadow-none focus:border-indigo-500 focus:ring-indigo-500">
                                    @foreach ($estados as $e)
                                        <option value="{{ $e->id }}" @selected($ticket->estado_actual_id == $e->id)>
                                            {{ $e->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="tecnico_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Técnico</label>
                                <select id="tecnico_id" name="tecnico_id" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-500 text-sm shadow-sm dark:shadow-none focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Sin asignar</option>
                                    @foreach ($tecnicos as $t)
                                        <option value="{{ $t->id }}" @selected($ticket->tecnico_id == $t->id)>
                                            {{ $t->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="comentario" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Comentario (opcional)</label>
                                <textarea id="comentario" name="comentario" rows="2" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-500 text-sm shadow-sm dark:shadow-none focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                            </div>
                            <button type="submit" class="w-full rounded-lg bg-indigo-600 dark:bg-indigo-500 px-4 py-2 text-sm font-semibold text-white shadow-sm dark:shadow-none hover:bg-indigo-500">
                                Actualizar
                            </button>
                        </form>
                        
                        <div class="my-6 border-t border-slate-100 dark:border-slate-700"></div>
                    @endcan

                    @if ($ticket->historial->isEmpty())
                        <p class="mt-6 text-sm text-slate-500 dark:text-slate-400">Sin movimientos registrados.</p>
                    @else
                        <ul class="mt-6 space-y-4">
                            @foreach ($ticket->historial as $h)
                                <li class="rounded-lg border border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 p-4">
                                    <div class="flex flex-wrap items-start justify-between gap-2">
                                        <x-ticket-status :estado="$h->estado" />
                                        <time
                                            class="whitespace-nowrap text-xs text-slate-500 dark:text-slate-400"
                                            datetime="{{ $h->fecha }}"
                                        >
                                            {{ \Carbon\Carbon::parse($h->fecha)->timezone(config('app.timezone'))->format('d/m/Y H:i') }}
                                        </time>
                                    </div>
                                    <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                                        {{ $h->usuario?->nombre ?? 'Sistema' }}
                                    </p>
                                    @if ($h->comentario)
                                        <p class="mt-2 text-sm text-slate-700 dark:text-slate-300 whitespace-pre-wrap">
                                            {{ $h->comentario }}
                                        </p>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
