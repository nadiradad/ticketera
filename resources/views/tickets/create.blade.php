@extends('layouts.app')

@section('content')
    <div class="space-y-8">
        <div>
            <a
                href="{{ route('tickets.index') }}"
                class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-400"
            >
                ← Volver al listado
            </a>
            <h1 class="mt-4 text-2xl font-bold tracking-tight text-slate-900 dark:text-white sm:text-3xl">Crear ticket</h1>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Completá los datos del cliente, equipo y estado inicial.</p>
        </div>

        <div class="max-w-2xl rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 p-6 shadow-sm dark:shadow-none sm:p-8">
            <form method="POST" action="{{ route('tickets.store') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="cliente_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Cliente</label>
                    <select
                        id="cliente_id"
                        name="cliente_id"
                        required
                        class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-500 text-sm shadow-sm dark:shadow-none focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        <option value="" disabled selected>Seleccionar…</option>
                        @foreach ($clientes as $c)
                            <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="equipo_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Equipo</label>
                    <select
                        id="equipo_id"
                        name="equipo_id"
                        required
                        class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-500 text-sm shadow-sm dark:shadow-none focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        <option value="" disabled selected>Seleccionar…</option>
                        @foreach ($equipos as $e)
                            <option value="{{ $e->id }}">{{ $e->marca }} {{ $e->modelo }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="tecnico_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Técnico</label>
                    <select
                        id="tecnico_id"
                        name="tecnico_id"
                        class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-500 text-sm shadow-sm dark:shadow-none focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        <option value="">Sin asignar</option>
                        @foreach ($tecnicos as $t)
                            <option value="{{ $t->id }}">{{ $t->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="estado_actual_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Estado</label>
                    <select
                        id="estado_actual_id"
                        name="estado_actual_id"
                        required
                        class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-500 text-sm shadow-sm dark:shadow-none focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        @foreach ($estados as $e)
                            <option value="{{ $e->id }}">{{ $e->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="descripcion" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Descripción</label>
                    <textarea
                        id="descripcion"
                        name="descripcion"
                        rows="4"
                        class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-500 text-sm shadow-sm dark:shadow-none focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Detalle del problema o solicitud…"
                    ></textarea>
                </div>

                <div class="flex flex-wrap gap-3 pt-2">
                    <button
                        type="submit"
                        class="inline-flex justify-center rounded-lg bg-indigo-600 dark:bg-indigo-500 px-4 py-2.5 text-sm font-semibold text-white shadow-sm dark:shadow-none transition hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        Guardar
                    </button>
                    <a
                        href="{{ route('tickets.index') }}"
                        class="inline-flex justify-center rounded-lg border border-slate-300 dark:border-slate-500 bg-white dark:bg-slate-800 px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 shadow-sm dark:shadow-none hover:bg-slate-50 dark:hover:bg-slate-900"
                    >
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
