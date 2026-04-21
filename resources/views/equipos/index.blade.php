@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Equipos</h1>
                <p class="mt-1 text-sm text-slate-600">Equipos asociados a cada cliente.</p>
            </div>
            <a
                href="{{ route('equipos.create') }}"
                class="inline-flex rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500"
            >
                Nuevo equipo
            </a>
        </div>

        @if (session('status'))
            <div class="rounded-lg bg-emerald-50 px-3 py-2 text-sm text-emerald-800 ring-1 ring-emerald-600/10">
                {{ session('status') }}
            </div>
        @endif

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-5 py-3 font-semibold text-slate-700 sm:px-6">Cliente</th>
                            <th class="px-3 py-3 font-semibold text-slate-700">Marca / modelo</th>
                            <th class="px-5 py-3 text-right font-semibold text-slate-700 sm:px-6"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($equipos as $e)
                            <tr class="hover:bg-slate-50/80">
                                <td class="px-5 py-3 text-slate-900 sm:px-6">
                                    {{ $e->cliente->nombre }} {{ $e->cliente->apellido }}
                                </td>
                                <td class="px-3 py-3 text-slate-600">{{ $e->marca }} {{ $e->modelo }}</td>
                                <td class="px-5 py-3 text-right sm:px-6">
                                    <a
                                        href="{{ route('equipos.edit', $e) }}"
                                        class="text-sm font-semibold text-indigo-600 hover:text-indigo-500"
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
