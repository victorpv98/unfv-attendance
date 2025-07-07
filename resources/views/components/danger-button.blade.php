<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-danger fw-semibold text-uppercase']) }}>
    {{ $slot }}
</button>