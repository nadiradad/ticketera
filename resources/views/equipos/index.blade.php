@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Equipos</h1>
                <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Equipos asociados a cada cliente.</p>
            </div>
            <a
                href="{{ route('equipos.create') }}"
                class="inline-flex rounded-lg bg-indigo-600 dark:bg-indigo-500 px-4 py-2 text-sm font-semibold text-white shadow-sm dark:shadow-none hover:bg-indigo-500"
            >
                Nuevo equipo
            </a>
        </div>

        @if (session('status'))
            <div class="rounded-lg bg-emerald-50 dark:bg-emerald-900/30 px-3 py-2 text-sm text-emerald-800 ring-1 ring-emerald-600/10">
                {{ session('status') }}
            </div>
        @endif

        <div class="overflow-hidden rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 shadow-sm dark:shadow-none">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-600 text-left text-sm">
                    <thead class="bg-slate-50 dark:bg-slate-900">
                        <tr>
                            <th class="px-5 py-3 font-semibold text-slate-700 dark:text-slate-300 sm:px-6">Cliente</th>
                            <th class="px-3 py-3 font-semibold text-slate-700 dark:text-slate-300">Marca / modelo</th>
                            <th class="px-5 py-3 text-right font-semibold text-slate-700 dark:text-slate-300 sm:px-6"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @foreach ($equipos as $e)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/80">
                                <td class="px-5 py-3 text-slate-900 dark:text-white sm:px-6">
                                    {{ $e->cliente->nombre }} {{ $e->cliente->apellido }}
                                </td>
                                <td class="px-3 py-3 text-slate-600 dark:text-slate-400">{{ $e->marca }} {{ $e->modelo }}</td>
                                <td class="px-5 py-3 text-right sm:px-6">
                                    <a
                                        href="{{ route('equipos.edit', $e) }}"
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
