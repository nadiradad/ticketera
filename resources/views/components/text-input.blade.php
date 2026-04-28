@props(['disabled' => false])

<input
    @disabled($disabled)
    {{ $attributes->merge(['class' => 'block w-full rounded-lg border-slate-300 dark:border-slate-500 text-sm shadow-sm dark:shadow-none focus:border-indigo-500 focus:ring-indigo-500']) }}
>
