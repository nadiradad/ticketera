@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-xl space-y-6">
        <div>
            <a href="{{ route('clientes.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">←
                Clientes</a>
            <h1 class="mt-3 text-2xl font-bold text-slate-900">Editar cliente</h1>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route('clientes.update', $cliente) }}" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-slate-700" for="nombre">Nombre</label>
                    <input
                        id="nombre"
                        name="nombre"
                        value="{{ old('nombre', $cliente->nombre) }}"
                        required
                        class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    />
                    @error('nombre')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700" for="apellido">Apellido</label>
                    <input
                        id="apellido"
                        name="apellido"
                        value="{{ old('apellido', $cliente->apellido) }}"
                        required
                        class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    />
                    @error('apellido')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700" for="dni">DNI</label>
                    <input
                        id="dni"
                        name="dni"
                        value="{{ old('dni', $cliente->dni) }}"
                        required
                        class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    />
                    @error('dni')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700" for="telefono">Teléfono</label>
                    <input
                        id="telefono"
                        name="telefono"
                        value="{{ old('telefono', $cliente->telefono) }}"
                        required
                        class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    />
                    @error('telefono')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700" for="correo">Correo (opcional)</label>
                    <input
                        id="correo"
                        type="email"
                        name="correo"
                        value="{{ old('correo', $cliente->correo) }}"
                        class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    />
                    @error('correo')
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
                        href="{{ route('clientes.index') }}"
                        class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50"
                    >
                        Cancelar
                    </a>
                </div>
            </form>

            <form
                method="POST"
                action="{{ route('clientes.destroy', $cliente) }}"
                class="mt-8 border-t border-slate-100 pt-6"
                onsubmit="return confirm('¿Eliminar este cliente? Se eliminarán sus equipos vinculados si la base lo permite.');"
            >
                @csrf
                @method('DELETE')
                <button type="submit" class="text-sm font-semibold text-red-600 hover:text-red-500">
                    Eliminar cliente
                </button>
            </form>
        </div>
    </div>
@endsection
