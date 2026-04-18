@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-sm font-medium mb-1.5']) }}
       style="color: #181d26; letter-spacing: 0.08px;">
    {{ $value ?? $slot }}
</label>
