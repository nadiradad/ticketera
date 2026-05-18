@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Clientes</h1>
                <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Alta y edición de personas que traen equipos.</p>
            </div>
            <a
                href="{{ route('clientes.create') }}"
                class="inline-flex rounded-lg bg-indigo-600 dark:bg-indigo-500 px-4 py-2 text-sm font-semibold text-white shadow-sm dark:shadow-none hover:bg-indigo-500"
            >
                Nuevo cliente
            </a>
        </div>

        @if (session('status'))
            <div class="rounded-lg bg-emerald-50 dark:bg-emerald-900/30 px-3 py-2 text-sm text-emerald-800 ring-1 ring-emerald-600/10">
                {{ session('status') }}
            </div>
        @endif

        <div class="mb-6 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 p-5 shadow-sm dark:shadow-none">
            <form method="GET" class="grid gap-4 sm:grid-cols-3 lg:grid-cols-6 items-end">
                <div class="sm:col-span-2">
                    <label for="dni" class="block text-xs font-medium text-slate-600 dark:text-slate-400">Buscar por DNI</label>
                    <input
                        id="dni"
                        name="dni"
                        type="search"
                        value="{{ request('dni') }}"
                        placeholder="Ej. 12345678"
                        class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-500 bg-white dark:bg-slate-900 text-sm shadow-sm dark:shadow-none focus:border-indigo-500 focus:ring-indigo-500"
                    />
                </div>
                <div class="sm:col-span-2">
                    <button
                        type="submit"
                        class="inline-flex w-full items-center justify-center rounded-lg bg-indigo-600 dark:bg-indigo-500 px-4 py-2 text-sm font-semibold text-white shadow-sm dark:shadow-none hover:bg-indigo-500"
                    >
                        Buscar
                    </button>
                </div>
                <div class="sm:col-span-2">
                    <a
                        href="{{ route('clientes.index') }}"
                        class="inline-flex w-full items-center justify-center rounded-lg border border-slate-300 dark:border-slate-500 bg-white dark:bg-slate-800 px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-300 shadow-sm dark:shadow-none hover:bg-slate-50 dark:hover:bg-slate-900"
                    >
                        Limpiar
                    </a>
                </div>
            </form>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 shadow-sm dark:shadow-none">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-600 text-left text-sm">
                    <thead class="bg-slate-50 dark:bg-slate-900">
                        <tr>
                            <th class="px-5 py-3 font-semibold text-slate-700 dark:text-slate-300 sm:px-6">Nombre</th>
                            <th class="px-3 py-3 font-semibold text-slate-700 dark:text-slate-300">DNI</th>
                            <th class="px-3 py-3 font-semibold text-slate-700 dark:text-slate-300">Teléfono</th>
                            <th class="px-5 py-3 text-right font-semibold text-slate-700 dark:text-slate-300 sm:px-6"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @foreach ($clientes as $c)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/80">
                                <td class="px-5 py-3 font-medium text-slate-900 dark:text-white sm:px-6">
                                    {{ $c->nombre }} {{ $c->apellido }}
                                </td>
                                <td class="px-3 py-3 text-slate-600 dark:text-slate-400">{{ $c->dni }}</td>
                                <td class="px-3 py-3 text-slate-600 dark:text-slate-400">{{ $c->telefono }}</td>
                                <td class="px-5 py-3 text-right sm:px-6">
                                    <a
                                        href="{{ route('clientes.edit', $c) }}"
                                        class="text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-400"
                                    >
                                        Editar
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
