@extends('layouts.app')

@section('content')
    <div class="space-y-8">
        <div>
            <a
                href="{{ route('tickets.show', $ticket->id) }}"
                class="text-sm font-medium text-indigo-600 hover:text-indigo-500"
            >
                ← Volver al detalle
            </a>
            <h1 class="mt-4 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                Editar ticket #{{ $ticket->id }}
            </h1>
            <p class="mt-1 text-sm text-slate-600">Actualizá asignación, equipo o estado.</p>
        </div>

        <div class="max-w-2xl rounded-xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <form method="POST" action="{{ route('tickets.update', $ticket->id) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="cliente_id" class="block text-sm font-medium text-slate-700">Cliente</label>
                    <select
                        id="cliente_id"
                        name="cliente_id"
                        required
                        class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        @foreach ($clientes as $c)
                            <option value="{{ $c->id }}" @selected($ticket->cliente_id == $c->id)>
                                {{ $c->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="equipo_id" class="block text-sm font-medium text-slate-700">Equipo</label>
                    <select
                        id="equipo_id"
                        name="equipo_id"
                        required
                        class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        @foreach ($equipos as $e)
                            <option value="{{ $e->id }}" @selected($ticket->equipo_id == $e->id)>
                                {{ $e->marca }} {{ $e->modelo }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="tecnico_id" class="block text-sm font-medium text-slate-700">Técnico</label>
                    <select
                        id="tecnico_id"
                        name="tecnico_id"
                        class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        <option value="">Sin asignar</option>
                        @foreach ($tecnicos as $t)
                            <option value="{{ $t->id }}" @selected($ticket->tecnico_id == $t->id)>
                                {{ $t->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="estado_actual_id" class="block text-sm font-medium text-slate-700">Estado</label>
                    <select
                        id="estado_actual_id"
                        name="estado_actual_id"
                        required
                        class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        @foreach ($estados as $e)
                            <option value="{{ $e->id }}" @selected($ticket->estado_actual_id == $e->id)>
                                {{ $e->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="descripcion" class="block text-sm font-medium text-slate-700">Descripción</label>
                    <textarea
                        id="descripcion"
                        name="descripcion"
                        rows="4"
                        class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >{{ $ticket->descripcion }}</textarea>
                </div>

                <div class="flex flex-wrap gap-3 pt-2">
                    <button
                        type="submit"
                        class="inline-flex justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        Actualizar
                    </button>
                    <a
                        href="{{ route('tickets.show', $ticket->id) }}"
                        class="inline-flex justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50"
                    >
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
