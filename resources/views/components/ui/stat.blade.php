@props([
    'title'   => '',
    'value'   => '',
    'desc'    => null,
    'icon'    => null,
    'trend'   => null,   // 'up' | 'down' | null
    'variant' => null,   // primary | secondary | accent | null
])

@php
$figureClass = match($variant) {
    'primary'   => 'text-primary',
    'secondary' => 'text-secondary',
    'accent'    => 'text-accent',
    default     => 'text-base-content/40',
};
@endphp

<div {{ $attributes->merge(['class' => 'stat']) }}>
    @if($icon)
        <div class="stat-figure {{ $figureClass }}">
            <x-icon :name="$icon" class="w-8 h-8" />
        </div>
    @endif

    <div class="stat-title">{{ $title }}</div>
    <div class="stat-value">{{ $value }}</div>

    @if($desc || $trend)
        <div class="stat-desc flex items-center gap-1">
            @if($trend === 'up')
                <x-icon name="trending-up" class="w-4 h-4 text-success" />
            @elseif($trend === 'down')
                <x-icon name="trending-down" class="w-4 h-4 text-error" />
            @endif
            @if($desc){{ $desc }}@endif
        </div>
    @endif
</div>
