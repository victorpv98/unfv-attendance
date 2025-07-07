@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'small text-danger list-unstyled mb-0']) }}>
        @foreach ((array) $messages as $message)
            <li class="mt-1">{{ $message }}</li>
        @endforeach
    </ul>
@endif