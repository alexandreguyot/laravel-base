@props([
    'variant' => 'default', // default | primary | secondary | accent | ghost | info | success | warning | error | outline
    'size'    => 'md',      // xs | sm | md | lg
])

@php
$variantClass = match($variant) {
    'primary'   => 'badge-primary',
    'secondary' => 'badge-secondary',
    'accent'    => 'badge-accent',
    'ghost'     => 'badge-ghost',
    'info'      => 'badge-info',
    'success'   => 'badge-success',
    'warning'   => 'badge-warning',
    'error'     => 'badge-error',
    'outline'   => 'badge-outline',
    default     => '',
};
$sizeClass = match($size) {
    'xs' => 'badge-xs',
    'sm' => 'badge-sm',
    'lg' => 'badge-lg',
    default => '',
};
@endphp

<span {{ $attributes->merge(['class' => "badge {$variantClass} {$sizeClass}"]) }}>
    {{ $slot }}
</span>
