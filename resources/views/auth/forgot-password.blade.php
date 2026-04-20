<x-guest-layout>
    <div class="mb-6">
        <h1 class="text-xl font-bold text-slate-900">Recuperar contraseña</h1>
        <p class="mt-2 text-sm leading-relaxed text-slate-600">
            Indicá el correo con el que te registraste y te enviaremos un enlace para elegir una nueva contraseña.
        </p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" value="Correo electrónico" />
            <x-text-input id="email" class="mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div class="flex flex-col gap-3 pt-1 sm:flex-row sm:justify-end">
            <a
                href="{{ route('login') }}"
                class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 sm:me-3"
            >
                Volver al inicio de sesión
            </a>

            <x-primary-button class="w-full sm:w-auto">
                Enviar enlace
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
