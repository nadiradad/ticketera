@php
    $u = auth()->user();
@endphp
<nav
    class="border-b border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 shadow-sm dark:shadow-none"
    x-data="{ open: false }"
>
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <div class="flex shrink-0 items-center gap-6 lg:gap-8">
                <a
                    href="{{ route('dashboard') }}"
                    class="text-lg font-semibold tracking-tight text-slate-900 dark:text-white"
                >
                    Ticketera
                </a>
                <div class="hidden flex-wrap items-center gap-1 md:flex">
                    <a
                        href="{{ route('dashboard') }}"
                        class="rounded-lg px-3 py-2 text-sm font-medium text-slate-600 dark:text-slate-400 transition hover:bg-slate-100 dark:hover:bg-slate-800/50 hover:text-slate-900 dark:hover:text-white {{ request()->routeIs('dashboard') ? 'bg-slate-100 dark:bg-slate-800/50 text-slate-900 dark:text-white' : '' }}"
                    >
                        Dashboard
                    </a>

                    @if ($u->isTecnico())
                        <a
                            href="{{ route('mis-tickets.index') }}"
                            class="rounded-lg px-3 py-2 text-sm font-medium text-slate-600 dark:text-slate-400 transition hover:bg-slate-100 dark:hover:bg-slate-800/50 hover:text-slate-900 dark:hover:text-white {{ request()->routeIs('mis-tickets.*') ? 'bg-slate-100 dark:bg-slate-800/50 text-slate-900 dark:text-white' : '' }}"
                        >
                            Mis tickets
                        </a>
                    @else
                        <a
                            href="{{ route('tickets.index') }}"
                            class="rounded-lg px-3 py-2 text-sm font-medium text-slate-600 dark:text-slate-400 transition hover:bg-slate-100 dark:hover:bg-slate-800/50 hover:text-slate-900 dark:hover:text-white {{ request()->routeIs('tickets.*') ? 'bg-slate-100 dark:bg-slate-800/50 text-slate-900 dark:text-white' : '' }}"
                        >
                            Tickets
                        </a>
                    @endif

                    @if ($u->isAdministrador() || $u->isRecepcionista())
                        <a
                            href="{{ route('clientes.index') }}"
                            class="rounded-lg px-3 py-2 text-sm font-medium text-slate-600 dark:text-slate-400 transition hover:bg-slate-100 dark:hover:bg-slate-800/50 hover:text-slate-900 dark:hover:text-white {{ request()->routeIs('clientes.*') ? 'bg-slate-100 dark:bg-slate-800/50 text-slate-900 dark:text-white' : '' }}"
                        >
                            Clientes
                        </a>
                        <a
                            href="{{ route('repuestos.index') }}"
                            class="rounded-lg px-3 py-2 text-sm font-medium text-slate-600 dark:text-slate-400 transition hover:bg-slate-100 dark:hover:bg-slate-800/50 hover:text-slate-900 dark:hover:text-white {{ request()->routeIs('repuestos.*') ? 'bg-slate-100 dark:bg-slate-800/50 text-slate-900 dark:text-white' : '' }}"
                        >
                            Repuestos
                        </a>
                    @endif

                    @if ($u->isAdministrador())
                        <a
                            href="{{ route('admin.staff.index') }}"
                            class="rounded-lg px-3 py-2 text-sm font-medium text-slate-600 dark:text-slate-400 transition hover:bg-slate-100 dark:hover:bg-slate-800/50 hover:text-slate-900 dark:hover:text-white {{ request()->routeIs('admin.staff.*') ? 'bg-slate-100 dark:bg-slate-800/50 text-slate-900 dark:text-white' : '' }}"
                        >
                            Personal
                        </a>
                    @endif
                </div>
            </div>

            <div class="hidden items-center gap-3 md:flex">
                <!-- Theme Toggle -->
                <button 
                    type="button" 
                    class="rounded-full p-1 text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/50 hover:text-slate-700 dark:hover:text-slate-300 focus:outline-none"
                    x-data="{ theme: localStorage.theme || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light') }"
                    @click="
                        theme = theme === 'dark' ? 'light' : 'dark';
                        localStorage.theme = theme;
                        if (theme === 'dark') {
                            document.documentElement.classList.add('dark');
                        } else {
                            document.documentElement.classList.remove('dark');
                        }
                    "
                >
                    <span class="sr-only">Cambiar tema</span>
                    <svg x-show="theme === 'dark'" style="display: none;" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                    </svg>
                    <svg x-show="theme !== 'dark'" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
                    </svg>
                </button>

                <!-- Notificaciones -->
                <div class="relative" x-data="{ notifOpen: false }" @click.away="notifOpen = false">
                    <button @click="notifOpen = !notifOpen" type="button" class="relative rounded-full p-1 text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/50 hover:text-slate-700 dark:hover:text-slate-300 focus:outline-none">
                        <span class="sr-only">Ver notificaciones</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                        </svg>
                        @if($u->unreadNotifications->count() > 0)
                            <span class="absolute top-0 right-0 block h-2.5 w-2.5 rounded-full bg-red-500 ring-2 ring-white dark:ring-slate-800"></span>
                        @endif
                    </button>
                    
                    <div x-show="notifOpen" style="display: none;" class="absolute right-0 z-10 mt-2 w-80 origin-top-right rounded-md bg-white dark:bg-slate-800 py-1 shadow-lg dark:shadow-none ring-1 ring-black ring-opacity-5 focus:outline-none">
                        <div class="px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 border-b border-slate-100 dark:border-slate-700">Notificaciones</div>
                        <div class="max-h-64 overflow-y-auto">
                            @forelse($u->unreadNotifications as $notification)
                                <form method="POST" action="{{ route('notificaciones.marcar-leida', $notification->id) }}">
                                    @csrf
                                    <button type="submit" class="block w-full px-4 py-3 text-left text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-900 transition border-b border-slate-50 last:border-0">
                                        {{ $notification->data['mensaje'] ?? 'Tienes una nueva notificación' }}
                                        <span class="block text-xs text-slate-400 dark:text-slate-500 mt-1">{{ $notification->created_at->diffForHumans() }}</span>
                                    </button>
                                </form>
                            @empty
                                <div class="px-4 py-3 text-sm text-slate-500 dark:text-slate-400">No hay notificaciones nuevas.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <span class="rounded-full bg-slate-100 dark:bg-slate-800/50 px-2.5 py-0.5 text-xs font-medium text-slate-700 dark:text-slate-300">
                    @if ($u->isAdministrador())
                        Administrador
                    @elseif ($u->isTecnico())
                        Técnico
                    @else
                        Recepción
                    @endif
                </span>
                <span class="text-sm text-slate-600 dark:text-slate-400">{{ $u->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button
                        type="submit"
                        class="rounded-lg border border-slate-300 dark:border-slate-500 bg-white dark:bg-slate-800 px-3 py-1.5 text-sm font-medium text-slate-700 dark:text-slate-300 shadow-sm dark:shadow-none transition hover:bg-slate-50 dark:hover:bg-slate-900"
                    >
                        Salir
                    </button>
                </form>
            </div>

            <button
                type="button"
                class="inline-flex items-center justify-center rounded-lg p-2 text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/50 md:hidden"
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
            class="border-t border-slate-100 dark:border-slate-700 pb-4 pt-2 md:hidden"
            x-cloak
            x-show="open"
            x-transition
        >
            <div class="flex flex-col gap-1">
                <a
                    href="{{ route('dashboard') }}"
                    class="rounded-lg px-3 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/50"
                >
                    Dashboard
                </a>
                @if ($u->isTecnico())
                    <a
                        href="{{ route('mis-tickets.index') }}"
                        class="rounded-lg px-3 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/50"
                    >
                        Mis tickets
                    </a>
                @else
                    <a
                        href="{{ route('tickets.index') }}"
                        class="rounded-lg px-3 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/50"
                    >
                        Tickets
                    </a>
                @endif
                @if ($u->isAdministrador() || $u->isRecepcionista())
                    <a
                        href="{{ route('clientes.index') }}"
                        class="rounded-lg px-3 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/50"
                    >
                        Clientes
                    </a>
                    <a
                        href="{{ route('repuestos.index') }}"
                        class="rounded-lg px-3 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/50"
                    >
                        Repuestos
                    </a>
                @endif
                @if ($u->isAdministrador())
                    <a
                        href="{{ route('admin.staff.index') }}"
                        class="rounded-lg px-3 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/50"
                    >
                        Personal
                    </a>
                @endif
                <div class="mt-2 flex items-center justify-between px-3">
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400 dark:text-slate-500">
                        {{ $u->name }}
                    </p>
                    <button 
                        type="button" 
                        class="rounded-full p-1 text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/50 hover:text-slate-700 dark:hover:text-slate-300 focus:outline-none"
                        x-data="{ theme: localStorage.theme || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light') }"
                        @click="
                            theme = theme === 'dark' ? 'light' : 'dark';
                            localStorage.theme = theme;
                            if (theme === 'dark') {
                                document.documentElement.classList.add('dark');
                            } else {
                                document.documentElement.classList.remove('dark');
                            }
                        "
                    >
                        <span class="sr-only">Cambiar tema</span>
                        <svg x-show="theme === 'dark'" style="display: none;" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                        </svg>
                        <svg x-show="theme !== 'dark'" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
                        </svg>
                    </button>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="px-3">
                    @csrf
                    <button
                        type="submit"
                        class="mt-1 w-full rounded-lg border border-slate-300 dark:border-slate-500 bg-white dark:bg-slate-800 px-3 py-2 text-sm font-medium text-slate-700 dark:text-slate-300"
                    >
                        Salir
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
