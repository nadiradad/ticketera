@props(['status'])

@if ($status)
    <div
        {{ $attributes->merge(['class' => 'rounded-lg bg-emerald-50 dark:bg-emerald-900/30 px-3 py-2 text-sm font-medium text-emerald-800 ring-1 ring-emerald-600/10']) }}
    >
        {{ $status }}
    </div>
@endif
