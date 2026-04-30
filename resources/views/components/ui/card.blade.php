@props([
    'title'     => null,
    'compact'   => false,
    'bordered'  => false,
    'shadow'    => true,
])

@php
$cardClass = 'card bg-base-100 '
    . ($shadow   ? 'shadow-sm '  : '')
    . ($bordered ? 'border border-base-300 ' : '')
    . ($attributes->get('class', ''));
$bodyClass = 'card-body ' . ($compact ? 'p-4' : 'p-6');
@endphp

<div {{ $attributes->except('class')->merge(['class' => $cardClass]) }}>
    <div class="{{ $bodyClass }}">
        @if($title)
            <h2 class="card-title text-base font-semibold mb-4">{{ $title }}</h2>
        @endif
        {{ $slot }}
    </div>
</div>
