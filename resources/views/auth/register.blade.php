<x-guest-layout>
    <div class="mb-6">
        <h1 class="text-xl font-bold text-slate-900">Crear cuenta</h1>
        <p class="mt-1 text-sm text-slate-600">Completá los datos para empezar a usar Ticketera.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="name" value="Nombre" />
            <x-text-input
                id="name"
                class="mt-1 w-full"
                type="text"
                name="name"
                :value="old('name')"
                required
                autofocus
                autocomplete="name"
            />
            <x-input-error :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" value="Correo electrónico" />
            <x-text-input
                id="email"
                class="mt-1 w-full"
                type="email"
                name="email"
                :value="old('email')"
                required
                autocomplete="username"
            />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div>
            <x-input-label for="password" value="Contraseña" />
            <x-text-input
                id="password"
                class="mt-1 w-full"
                type="password"
                name="password"
                required
                autocomplete="new-password"
            />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div>
            <x-input-label for="password_confirmation" value="Confirmar contraseña" />
            <x-text-input
                id="password_confirmation"
                class="mt-1 w-full"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
            />
            <x-input-error :messages="$errors->get('password_confirmation')" />
        </div>

        <div class="flex flex-col-reverse gap-3 pt-1 sm:flex-row sm:justify-end">
            <a
                class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-center text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 sm:me-3"
                href="{{ route('login') }}"
            >
                Ya tengo cuenta
            </a>

            <x-primary-button class="w-full sm:w-auto">
                Registrarme
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
