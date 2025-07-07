@props(['active'])

@php
$classes = ($active ?? false)
            ? 'nav-link active d-flex align-items-center px-1 pt-1 pb-1 small fw-medium text-dark border-bottom border-primary border-2'
            : 'nav-link d-flex align-items-center px-1 pt-1 pb-1 small fw-medium text-muted border-bottom border-transparent border-2';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>