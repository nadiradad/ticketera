@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-xl space-y-6">
        <div>
            <a href="{{ route('equipos.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">←
                Equipos</a>
            <h1 class="mt-3 text-2xl font-bold text-slate-900">Editar equipo</h1>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route('equipos.update', $equipo) }}" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-slate-700" for="cliente_id">Cliente</label>
                    <select
                        id="cliente_id"
                        name="cliente_id"
                        required
                        class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        @foreach ($clientes as $c)
                            <option value="{{ $c->id }}" @selected(old('cliente_id', $equipo->cliente_id) == $c->id)>
                                {{ $c->nombre }} {{ $c->apellido }}
                            </option>
                        @endforeach
                    </select>
                    @error('cliente_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700" for="marca">Marca</label>
                    <input
                        id="marca"
                        name="marca"
                        value="{{ old('marca', $equipo->marca) }}"
                        required
                        class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    />
                    @error('marca')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700" for="modelo">Modelo</label>
                    <input
                        id="modelo"
                        name="modelo"
                        value="{{ old('modelo', $equipo->modelo) }}"
                        required
                        class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    />
                    @error('modelo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700" for="descripcion">Descripción (opcional)</label>
                    <textarea
                        id="descripcion"
                        name="descripcion"
                        rows="3"
                        class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >{{ old('descripcion', $equipo->descripcion) }}</textarea>
                    @error('descripcion')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex flex-wrap gap-3 pt-2">
                    <button
                        type="submit"
                        class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500"
                    >
                        Actualizar
                    </button>
                    <a
                        href="{{ route('equipos.index') }}"
                        class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50"
                    >
                        Cancelar
                    </a>
                </div>
            </form>

            <form
                method="POST"
                action="{{ route('equipos.destroy', $equipo) }}"
                class="mt-8 border-t border-slate-100 pt-6"
                onsubmit="return confirm('¿Eliminar este equipo?');"
            >
                @csrf
                @method('DELETE')
                <button type="submit" class="text-sm font-semibold text-red-600 hover:text-red-500">
                    Eliminar equipo
                </button>
            </form>
        </div>
    </div>
@endsection
