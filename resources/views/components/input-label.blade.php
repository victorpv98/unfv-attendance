@props(['value'])

<label {{ $attributes->merge(['class' => 'form-label fw-medium small text-muted']) }}>
    {{ $value ?? $slot }}
</label>