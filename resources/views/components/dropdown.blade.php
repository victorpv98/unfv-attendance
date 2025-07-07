@props(['align' => 'right', 'width' => '48', 'contentClasses' => 'py-1 bg-white'])

@php
$alignmentClasses = match ($align) {
    'left' => 'dropdown-menu-start',
    'top' => 'dropup',
    default => 'dropdown-menu-end',
};

$width = match ($width) {
    '48' => 'w-auto',
    'full' => 'w-100',
    default => '',
};

$dropDirection = $align === 'top' ? 'dropup' : 'dropdown';

// Convertir contentClasses de Tailwind a Bootstrap
$contentClassesBootstrap = str_replace([
    'py-1', 
    'bg-white'
], [
    'py-1', 
    'bg-white'
], $contentClasses);
@endphp

<div class="dropdown {{ $dropDirection }}">
    <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        {{ $trigger }}
    </button>
    
    <ul class="dropdown-menu {{ $alignmentClasses }} {{ $width }} shadow">
        <div class="{{ $contentClassesBootstrap }}">
            {{ $content }}
        </div>
    </ul>
</div>