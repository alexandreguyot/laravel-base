@props([
    'variant' => 'primary',   // primary | secondary | accent | ghost | link | success | warning | error | info | outline
    'size'    => 'md',        // xs | sm | md | lg
    'icon'    => null,
    'iconRight' => null,
    'loading' => false,
    'type'    => 'button',
    'href'    => null,
])

@php
$variantClass = match($variant) {
    'primary'   => 'btn-primary',
    'secondary' => 'btn-secondary',
    'accent'    => 'btn-accent',
    'ghost'     => 'btn-ghost',
    'link'      => 'btn-link',
    'success'   => 'btn-success',
    'warning'   => 'btn-warning',
    'error'     => 'btn-error',
    'info'      => 'btn-info',
    'outline'   => 'btn-outline',
    default     => 'btn-primary',
};
$sizeClass = match($size) {
    'xs' => 'btn-xs',
    'sm' => 'btn-sm',
    'lg' => 'btn-lg',
    default => '',
};
$classes = "btn {$variantClass} {$sizeClass} gap-2 " . ($attributes->get('class', ''));
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->except('class')->merge(['class' => $classes]) }}>
        @if($icon)<x-icon :name="$icon" class="w-4 h-4" />@endif
        {{ $slot }}
        @if($iconRight)<x-icon :name="$iconRight" class="w-4 h-4" />@endif
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->except('class')->merge(['class' => $classes]) }}
        @if($loading) disabled @endif>
        @if($loading)
            <span class="loading loading-spinner loading-xs"></span>
        @elseif($icon)
            <x-icon :name="$icon" class="w-4 h-4" />
        @endif
        {{ $slot }}
        @if($iconRight && !$loading)<x-icon :name="$iconRight" class="w-4 h-4" />@endif
    </button>
@endif
