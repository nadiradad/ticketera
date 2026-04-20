<x-guest-layout>
    <div class="mb-6">
        <h1 class="text-xl font-bold text-slate-900">Iniciar sesión</h1>
        <p class="mt-1 text-sm text-slate-600">Ingresá con tu cuenta para acceder al panel.</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" value="Correo electrónico" />
            <x-text-input
                id="email"
                class="mt-1 w-full"
                type="email"
                name="email"
                :value="old('email')"
                required
                autofocus
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
                autocomplete="current-password"
            />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div class="flex items-center gap-2">
            <input
                id="remember_me"
                type="checkbox"
                class="rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                name="remember"
            />
            <label for="remember_me" class="text-sm text-slate-600">Recordarme en este equipo</label>
        </div>

        <div class="flex flex-col-reverse gap-3 pt-1 sm:flex-row sm:items-center sm:justify-between">
            @if (Route::has('password.request'))
                <a
                    class="text-center text-sm font-medium text-indigo-600 hover:text-indigo-500 sm:text-left"
                    href="{{ route('password.request') }}"
                >
                    ¿Olvidaste tu contraseña?
                </a>
            @else
                <span></span>
            @endif

            <x-primary-button class="w-full sm:w-auto">
                Entrar
            </x-primary-button>
        </div>
    </form>

    @if (Route::has('register'))
        <p class="mt-8 border-t border-slate-100 pt-6 text-center text-sm text-slate-600">
            ¿No tenés cuenta?
            <a href="{{ route('register') }}" class="font-semibold text-indigo-600 hover:text-indigo-500">
                Registrate
            </a>
        </p>
    @endif
</x-guest-layout>
