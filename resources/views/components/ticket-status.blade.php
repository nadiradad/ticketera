@props(['estado'])

@php
    $nombre = is_string($estado) ? $estado : ($estado->nombre ?? 'Desconocido');
    $estadoStr = strtolower($nombre);
    
    $clases = match (true) {
        in_array($estadoStr, ['abierto', 'pendiente']) 
            => 'bg-amber-200 text-amber-900 dark:bg-amber-600/30 dark:text-amber-300 ring-1 ring-amber-500/30 dark:ring-amber-500/50',
            
        in_array($estadoStr, ['en proceso', 'en reparacion', 'asignado']) 
            => 'bg-sky-200 text-sky-900 dark:bg-sky-600/30 dark:text-sky-300 ring-1 ring-sky-500/30 dark:ring-sky-500/50',
            
        in_array($estadoStr, ['cerrado ok', 'resuelto', 'entregado']) 
            => 'bg-emerald-200 text-emerald-900 dark:bg-emerald-600/30 dark:text-emerald-300 ring-1 ring-emerald-500/30 dark:ring-emerald-500/50',
            
        in_array($estadoStr, ['cerrado sin exito', 'cancelado', 'rechazado']) 
            => 'bg-red-200 text-red-900 dark:bg-red-600/30 dark:text-red-300 ring-1 ring-red-500/30 dark:ring-red-500/50',
            
        default 
            => 'bg-slate-200 text-slate-800 dark:bg-slate-600/30 dark:text-slate-300 ring-1 ring-slate-400/30 dark:ring-slate-400/50',
    };
@endphp

<span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $clases }}">
    {{ $nombre }}
</span>
