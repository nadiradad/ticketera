<x-guest-layout>
    <div class="mb-6">
        <h1 class="text-xl font-bold text-slate-900 dark:text-white">Confirmar contraseña</h1>
        <p class="mt-2 text-sm leading-relaxed text-slate-600 dark:text-slate-400">
            Esta sección es segura. Ingresá tu contraseña actual para continuar.
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="password" value="Contraseña" />
            <x-text-input
                id="password"
                class="mt-1 w-full"
                type="password"
                name="password"
                required
                autocomplete="current-password"
            />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div class="pt-1 sm:flex sm:justify-end">
            <x-primary-button class="w-full sm:w-auto">
                Continuar
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
