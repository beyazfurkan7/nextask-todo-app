@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-semibold text-sm text-slate-700 dark:text-slate-300 mb-1 transition-colors duration-200']) }}>
    {{ $value ?? $slot }}
</label>
