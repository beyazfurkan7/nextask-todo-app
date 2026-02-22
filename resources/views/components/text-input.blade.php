@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-300 focus:border-cyan-500 dark:focus:border-cyan-400 focus:ring focus:ring-cyan-500/20 rounded-lg shadow-sm transition-colors duration-200']) !!}>
