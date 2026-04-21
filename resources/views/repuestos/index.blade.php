@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Repuestos</h1>
                <p class="mt-1 text-sm text-slate-600">Catálogo para cargar en tickets.</p>
            </div>
            <a
                href="{{ route('repuestos.create') }}"
                class="inline-flex rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500"
            >
                Nuevo repuesto
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
                            <th class="px-5 py-3 font-semibold text-slate-700 sm:px-6">Nombre</th>
                            <th class="px-3 py-3 font-semibold text-slate-700">Precio base</th>
                            <th class="px-5 py-3 text-right font-semibold text-slate-700 sm:px-6"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($repuestos as $r)
                            <tr class="hover:bg-slate-50/80">
                                <td class="px-5 py-3 font-medium text-slate-900 sm:px-6">{{ $r->nombre }}</td>
                                <td class="px-3 py-3 text-slate-600">${{ number_format($r->precio_base, 2, ',', '.') }}</td>
                                <td class="px-5 py-3 text-right sm:px-6">
                                    <a
                                        href="{{ route('repuestos.edit', $r) }}"
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
