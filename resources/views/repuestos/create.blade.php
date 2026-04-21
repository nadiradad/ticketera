@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-xl space-y-6">
        <div>
            <a href="{{ route('repuestos.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">←
                Repuestos</a>
            <h1 class="mt-3 text-2xl font-bold text-slate-900">Nuevo repuesto</h1>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route('repuestos.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-slate-700" for="nombre">Nombre</label>
                    <input
                        id="nombre"
                        name="nombre"
                        value="{{ old('nombre') }}"
                        required
                        class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    />
                    @error('nombre')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700" for="descripcion">Descripción (opcional)</label>
                    <textarea
                        id="descripcion"
                        name="descripcion"
                        rows="2"
                        class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700" for="precio_base">Precio base</label>
                    <input
                        id="precio_base"
                        type="number"
                        step="0.01"
                        name="precio_base"
                        value="{{ old('precio_base') }}"
                        required
                        class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    />
                    @error('precio_base')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex gap-3 pt-2">
                    <button
                        type="submit"
                        class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500"
                    >
                        Guardar
                    </button>
                    <a
                        href="{{ route('repuestos.index') }}"
                        class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50"
                    >
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
