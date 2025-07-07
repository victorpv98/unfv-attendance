@props(['active'])

@php
$classes = ($active ?? false)
    ? 'd-block w-100 px-3 py-2 text-start fw-medium text-primary bg-light border-start border-4 border-primary text-decoration-none'
    : 'd-block w-100 px-3 py-2 text-start fw-medium text-muted text-decoration-none border-start border-4 border-transparent';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>