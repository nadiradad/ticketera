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

    {{-- CLIENTE --}}
    <div class="border-b border-slate-200 dark:border-slate-600 pb-6">
        <h2 class="mb-4 text-lg font-semibold text-slate-900 dark:text-white">
            Datos del cliente
        </h2>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                    Nombre
                </label>

                <input
                    type="text"
                    name="nombre"
                    value="{{ old('nombre') }}"
                    required
                    class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-500 text-sm"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                    Apellido
                </label>

                <input
                    type="text"
                    name="apellido"
                    value="{{ old('apellido') }}"
                    required
                    class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-500 text-sm"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                    DNI
                </label>

                <input
                    type="text"
                    name="dni"
                    value="{{ old('dni') }}"
                    required
                    class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-500 text-sm"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                    Teléfono
                </label>

                <input
                    type="text"
                    name="telefono"
                    value="{{ old('telefono') }}"
                    required
                    class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-500 text-sm"
                >
            </div>

            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                    Correo (opcional)
                </label>

                <input
                    type="email"
                    name="correo"
                    value="{{ old('correo') }}"
                    class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-500 text-sm"
                >
            </div>

        </div>
    </div>

    {{-- EQUIPO --}}
    <div class="border-b border-slate-200 dark:border-slate-600 pb-6">
        <h2 class="mb-4 text-lg font-semibold text-slate-900 dark:text-white">
            Datos del equipo
        </h2>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                    Marca
                </label>

                <input
                    type="text"
                    name="marca"
                    value="{{ old('marca') }}"
                    required
                    class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-500 text-sm"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                    Modelo
                </label>

                <input
                    type="text"
                    name="modelo"
                    value="{{ old('modelo') }}"
                    required
                    class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-500 text-sm"
                >
            </div>

            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                    Descripción del equipo
                </label>

                <textarea
                    name="descripcion_equipo"
                    rows="3"
                    class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-500 text-sm"
                >{{ old('descripcion_equipo') }}</textarea>
            </div>

        </div>
    </div>

    {{-- TICKET --}}
    <div>
        <h2 class="mb-4 text-lg font-semibold text-slate-900 dark:text-white">
            Datos del ticket
        </h2>

        <div class="space-y-4">

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                    Técnico
                </label>

                <select
                    name="tecnico_id"
                    class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-500 text-sm"
                >
                    <option value="">Sin asignar</option>

                    @foreach ($tecnicos as $t)
                        <option value="{{ $t->id }}">
                            {{ $t->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                    Estado
                </label>

                <select
                    name="estado_actual_id"
                    required
                    class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-500 text-sm"
                >
                    @foreach ($estados as $e)
                        <option value="{{ $e->id }}">
                            {{ $e->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                    Descripción del problema
                </label>

                <textarea
                    name="descripcion"
                    rows="4"
                    class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-500 text-sm"
                >{{ old('descripcion') }}</textarea>
            </div>

            

        </div>
    </div>

    <div class="flex flex-wrap gap-3 pt-2">

        <button
            type="submit"
            class="inline-flex justify-center rounded-lg bg-indigo-600 dark:bg-indigo-500 px-4 py-2.5 text-sm font-semibold text-white"
        >
            Guardar Ticket
        </button>

        <a
            href="{{ route('tickets.index') }}"
            class="inline-flex justify-center rounded-lg border border-slate-300 dark:border-slate-500 bg-white dark:bg-slate-800 px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300"
        >
            Cancelar
        </a>

    </div>

</form>

<script>

    function agregarRepuesto() {

        const container = document.getElementById('repuestos-container');

        const nuevo = document.createElement('div');

        nuevo.classList.add(
            'grid',
            'grid-cols-1',
            'gap-4',
            'sm:grid-cols-2',
            'repuesto-item'
        );

        nuevo.innerHTML = `
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                    Tipo de repuesto
                </label>

                <input
                    type="text"
                    name="repuesto_tipo[]"
                    class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-500 text-sm"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                    Precio
                </label>

                <input
                    type="number"
                    step="0.01"
                    name="repuesto_precio[]"
                    value="0"
                    class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-500 text-sm"
                >
            </div>
        `;

        container.appendChild(nuevo);
    }

</script>
        </div>
    
@endsection
