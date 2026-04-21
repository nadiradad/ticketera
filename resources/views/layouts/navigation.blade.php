@php
    $u = auth()->user();
@endphp
<nav
    class="border-b border-slate-200 bg-white shadow-sm"
    x-data="{ open: false }"
>
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <div class="flex shrink-0 items-center gap-6 lg:gap-8">
                <a
                    href="{{ route('dashboard') }}"
                    class="text-lg font-semibold tracking-tight text-slate-900"
                >
                    Ticketera
                </a>
                <div class="hidden flex-wrap items-center gap-1 md:flex">
                    <a
                        href="{{ route('dashboard') }}"
                        class="rounded-lg px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900 {{ request()->routeIs('dashboard') ? 'bg-slate-100 text-slate-900' : '' }}"
                    >
                        Dashboard
                    </a>

                    @if ($u->isTecnico())
                        <a
                            href="{{ route('mis-tickets.index') }}"
                            class="rounded-lg px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900 {{ request()->routeIs('mis-tickets.*') ? 'bg-slate-100 text-slate-900' : '' }}"
                        >
                            Mis tickets
                        </a>
                    @else
                        <a
                            href="{{ route('tickets.index') }}"
                            class="rounded-lg px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900 {{ request()->routeIs('tickets.*') ? 'bg-slate-100 text-slate-900' : '' }}"
                        >
                            Tickets
                        </a>
                    @endif

                    @if ($u->isAdministrador() || $u->isRecepcionista())
                        <a
                            href="{{ route('clientes.index') }}"
                            class="rounded-lg px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900 {{ request()->routeIs('clientes.*') ? 'bg-slate-100 text-slate-900' : '' }}"
                        >
                            Clientes
                        </a>
                        <a
                            href="{{ route('equipos.index') }}"
                            class="rounded-lg px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900 {{ request()->routeIs('equipos.*') ? 'bg-slate-100 text-slate-900' : '' }}"
                        >
                            Equipos
                        </a>
                        <a
                            href="{{ route('repuestos.index') }}"
                            class="rounded-lg px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900 {{ request()->routeIs('repuestos.*') ? 'bg-slate-100 text-slate-900' : '' }}"
                        >
                            Repuestos
                        </a>
                    @endif

                    @if ($u->isAdministrador())
                        <a
                            href="{{ route('admin.staff.index') }}"
                            class="rounded-lg px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900 {{ request()->routeIs('admin.staff.*') ? 'bg-slate-100 text-slate-900' : '' }}"
                        >
                            Personal
                        </a>
                    @endif
                </div>
            </div>

            <div class="hidden items-center gap-3 md:flex">
                <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-700">
                    @if ($u->isAdministrador())
                        Administrador
                    @elseif ($u->isTecnico())
                        Técnico
                    @else
                        Recepción
                    @endif
                </span>
                <span class="text-sm text-slate-600">{{ $u->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button
                        type="submit"
                        class="rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50"
                    >
                        Salir
                    </button>
                </form>
            </div>

            <button
                type="button"
                class="inline-flex items-center justify-center rounded-lg p-2 text-slate-600 hover:bg-slate-100 md:hidden"
                @click="open = ! open"
                aria-expanded="false"
                x-bind:aria-expanded="open"
            >
                <span class="sr-only">Abrir menú</span>
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>
        </div>

        <div
            class="border-t border-slate-100 pb-4 pt-2 md:hidden"
            x-cloak
            x-show="open"
            x-transition
        >
            <div class="flex flex-col gap-1">
                <a
                    href="{{ route('dashboard') }}"
                    class="rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100"
                >
                    Dashboard
                </a>
                @if ($u->isTecnico())
                    <a
                        href="{{ route('mis-tickets.index') }}"
                        class="rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100"
                    >
                        Mis tickets
                    </a>
                @else
                    <a
                        href="{{ route('tickets.index') }}"
                        class="rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100"
                    >
                        Tickets
                    </a>
                @endif
                @if ($u->isAdministrador() || $u->isRecepcionista())
                    <a
                        href="{{ route('clientes.index') }}"
                        class="rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100"
                    >
                        Clientes
                    </a>
                    <a
                        href="{{ route('equipos.index') }}"
                        class="rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100"
                    >
                        Equipos
                    </a>
                    <a
                        href="{{ route('repuestos.index') }}"
                        class="rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100"
                    >
                        Repuestos
                    </a>
                @endif
                @if ($u->isAdministrador())
                    <a
                        href="{{ route('admin.staff.index') }}"
                        class="rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100"
                    >
                        Personal
                    </a>
                @endif
                <p class="mt-2 px-3 text-xs font-medium uppercase tracking-wide text-slate-400">
                    {{ $u->name }}
                </p>
                <form method="POST" action="{{ route('logout') }}" class="px-3">
                    @csrf
                    <button
                        type="submit"
                        class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-medium text-slate-700"
                    >
                        Salir
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
