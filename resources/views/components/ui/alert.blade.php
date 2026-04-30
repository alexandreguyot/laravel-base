@props([
    'variant'     => 'info',   // info | success | warning | error
    'dismissible' => false,
    'icon'        => null,
])

@php
$variantClass = match($variant) {
    'success' => 'alert-success',
    'warning' => 'alert-warning',
    'error'   => 'alert-error',
    default   => 'alert-info',
};
$defaultIcon = match($variant) {
    'success' => 'circle-check',
    'warning' => 'alert-triangle',
    'error'   => 'x-circle',
    default   => 'info',
};
$iconName = $icon ?? $defaultIcon;
@endphp

<div {{ $attributes->merge(['class' => "alert {$variantClass} shadow-sm"]) }}>
    <x-icon :name="$iconName" class="w-5 h-5 shrink-0" />
    <span class="flex-1">{{ $slot }}</span>
    @if($dismissible)
        <button type="button" onclick="this.closest('.alert').remove()" class="btn btn-ghost btn-xs btn-circle">
            <x-icon name="x" class="w-4 h-4" />
        </button>
    @endif
</div>
