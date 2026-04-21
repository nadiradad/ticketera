@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Personal</h1>
                <p class="mt-1 text-sm text-slate-600">
                    Alta de técnicos y recepcionistas. Cada usuario puede iniciar sesión con su correo.
                </p>
            </div>
            <a
                href="{{ route('admin.staff.create') }}"
                class="inline-flex rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500"
            >
                Nuevo usuario
            </a>
        </div>

        @if (session('status'))
            <div class="rounded-lg bg-emerald-50 px-3 py-2 text-sm text-emerald-800 ring-1 ring-emerald-600/10">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-lg bg-red-50 px-3 py-2 text-sm text-red-800 ring-1 ring-red-600/10">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-5 py-3 font-semibold text-slate-700 sm:px-6">Nombre</th>
                            <th class="px-3 py-3 font-semibold text-slate-700">Correo</th>
                            <th class="px-3 py-3 font-semibold text-slate-700">Rol</th>
                            <th class="px-5 py-3 text-right font-semibold text-slate-700 sm:px-6"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($users as $u)
                            <tr class="hover:bg-slate-50/80">
                                <td class="px-5 py-3 font-medium text-slate-900 sm:px-6">{{ $u->name }}</td>
                                <td class="px-3 py-3 text-slate-600">{{ $u->email }}</td>
                                <td class="px-3 py-3">
                                    <span
                                        class="inline-flex rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-800"
                                    >
                                        @if ($u->isAdministrador())
                                            Administrador
                                        @elseif ($u->isTecnico())
                                            Técnico
                                        @else
                                            Recepcionista
                                        @endif
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-right sm:px-6">
                                    @unless ($u->isAdministrador())
                                        <a
                                            href="{{ route('admin.staff.edit', $u) }}"
                                            class="mr-3 text-sm font-semibold text-indigo-600 hover:text-indigo-500"
                                        >
                                            Editar
                                        </a>
                                        @unless ($u->id === auth()->id())
                                            <form
                                                method="POST"
                                                action="{{ route('admin.staff.destroy', $u) }}"
                                                class="inline"
                                                onsubmit="return confirm('¿Eliminar este usuario?');"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    class="text-sm font-semibold text-red-600 hover:text-red-500"
                                                >
                                                    Eliminar
                                                </button>
                                            </form>
                                        @endunless
                                    @endunless
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
