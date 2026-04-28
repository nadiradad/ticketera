@extends('layouts.app')

@section('content')
    <div class="space-y-8">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white sm:text-3xl">Tickets</h1>
                <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
                    Listado completo con altas, edición y baja.
                </p>
            </div>
            @can('create', App\Models\Ticket::class)
                <a
                    href="{{ route('tickets.create') }}"
                    class="inline-flex items-center justify-center rounded-lg bg-indigo-600 dark:bg-indigo-500 px-4 py-2.5 text-sm font-semibold text-white shadow-sm dark:shadow-none transition hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Nuevo ticket
                </a>
            @endcan
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 shadow-sm dark:shadow-none">
            @if ($tickets->isEmpty())
                <div class="px-5 py-16 text-center sm:px-6">
                    <p class="text-sm font-medium text-slate-900 dark:text-white">Todavía no hay tickets</p>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Creá el primero para empezar a trabajar la cola.</p>
                    @can('create', App\Models\Ticket::class)
                        <a
                            href="{{ route('tickets.create') }}"
                            class="mt-4 inline-flex rounded-lg bg-indigo-600 dark:bg-indigo-500 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500"
                        >
                            Crear ticket
                        </a>
                    @endcan
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-600 text-left text-sm">
                        <thead class="bg-slate-50 dark:bg-slate-900">
                            <tr>
                                <th scope="col" class="px-5 py-3 font-semibold text-slate-700 dark:text-slate-300 sm:px-6">ID</th>
                                <th scope="col" class="px-3 py-3 font-semibold text-slate-700 dark:text-slate-300">Cliente</th>
                                <th scope="col" class="px-3 py-3 font-semibold text-slate-700 dark:text-slate-300">Estado</th>
                                <th scope="col" class="hidden px-3 py-3 font-semibold text-slate-700 dark:text-slate-300 md:table-cell">
                                    Técnico
                                </th>
                                <th scope="col" class="px-5 py-3 text-right font-semibold text-slate-700 dark:text-slate-300 sm:px-6">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700 bg-white dark:bg-slate-800">
                            @foreach ($tickets as $ticket)
                                <tr class="transition hover:bg-slate-50 dark:hover:bg-slate-900/80 cursor-pointer" onclick="window.location='{{ route('tickets.show', $ticket) }}'">
                                    <td class="whitespace-nowrap px-5 py-3 font-mono text-xs text-slate-600 dark:text-slate-400 sm:px-6">
                                        #{{ $ticket->id }}
                                    </td>
                                    <td class="px-3 py-3 font-medium text-slate-900 dark:text-white">
                                        {{ $ticket->cliente->nombre }}
                                    </td>
                                    <td class="px-3 py-3">
                                        <x-ticket-status :estado="$ticket->estadoActual" />
                                    </td>
                                    <td class="hidden whitespace-nowrap px-3 py-3 text-slate-600 dark:text-slate-400 md:table-cell">
                                        {{ $ticket->tecnico->nombre ?? '—' }}
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-3 text-right sm:px-6">
                                        @can('view', $ticket)
                                            <a
                                                href="{{ route('tickets.show', $ticket) }}"
                                                class="mr-1 inline-flex rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-2.5 py-1 text-xs font-semibold text-slate-700 dark:text-slate-300 shadow-sm dark:shadow-none hover:bg-slate-50 dark:hover:bg-slate-900"
                                            >
                                                Ver
                                            </a>
                                        @endcan
                                        @can('editBase', $ticket)
                                            <a
                                                href="{{ route('tickets.edit', $ticket) }}"
                                                class="mr-1 inline-flex rounded-lg bg-indigo-600 dark:bg-indigo-500 px-2.5 py-1 text-xs font-semibold text-white shadow-sm dark:shadow-none hover:bg-indigo-500"
                                            >
                                                Editar
                                            </a>
                                        @endcan
                                        @can('delete', $ticket)
                                            <form
                                                action="{{ route('tickets.destroy', $ticket) }}"
                                                method="POST"
                                                class="inline"
                                                onsubmit="return confirm('¿Eliminar este ticket? Esta acción no se puede deshacer.');"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    class="inline-flex rounded-lg border border-red-200 bg-white dark:bg-slate-800 px-2.5 py-1 text-xs font-semibold text-red-700 dark:text-red-400 shadow-sm dark:shadow-none hover:bg-red-50 dark:hover:bg-red-900/30"
                                                >
                                                    Eliminar
                                                </button>
                                            </form>
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
