@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-lg space-y-6">
        <div>
            <a href="{{ route('admin.staff.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">←
                Personal</a>
            <h1 class="mt-3 text-2xl font-bold text-slate-900">Editar usuario</h1>
            <p class="mt-1 text-sm text-slate-600">{{ $user->email }}</p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route('admin.staff.update', $user) }}" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-slate-700" for="name">Nombre</label>
                    <input
                        id="name"
                        name="name"
                        value="{{ old('name', $user->name) }}"
                        required
                        class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    />
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700" for="email">Correo</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email', $user->email) }}"
                        required
                        class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    />
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700" for="rol">Rol</label>
                    <select
                        id="rol"
                        name="rol"
                        required
                        class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        <option value="tecnico" @selected(old('rol', $user->rol) === 'tecnico')>Técnico</option>
                        <option value="recepcionista" @selected(old('rol', $user->rol) === 'recepcionista')>
                            Recepcionista
                        </option>
                    </select>
                    @error('rol')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700" for="password">Nueva contraseña (opcional)</label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    />
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700" for="password_confirmation">
                        Confirmar contraseña
                    </label>
                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    />
                </div>
                <div class="flex gap-3 pt-2">
                    <button
                        type="submit"
                        class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500"
                    >
                        Guardar cambios
                    </button>
                    <a
                        href="{{ route('admin.staff.index') }}"
                        class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50"
                    >
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
