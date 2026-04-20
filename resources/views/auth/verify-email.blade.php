<x-guest-layout>
    <div class="mb-6">
        <h1 class="text-xl font-bold text-slate-900">Verificá tu correo</h1>
        <p class="mt-2 text-sm leading-relaxed text-slate-600">
            Gracias por registrarte. Antes de continuar, confirmá tu dirección de correo con el enlace que te enviamos. Si no lo recibiste, podés pedir otro.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div
            class="mb-4 rounded-lg bg-emerald-50 px-3 py-2 text-sm font-medium text-emerald-800 ring-1 ring-emerald-600/10"
        >
            Te enviamos un nuevo enlace de verificación al correo que usaste al registrarte.
        </div>
    @endif

    <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <x-primary-button>
                Reenviar correo de verificación
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button
                type="submit"
                class="text-sm font-medium text-slate-600 underline decoration-slate-300 underline-offset-2 transition hover:text-slate-900"
            >
                Cerrar sesión
            </button>
        </form>
    </div>
</x-guest-layout>
