@props([
    'id'    => 'modal',
    'title' => null,
    'size'  => 'md',   // sm | md | lg | xl
])

@php
$sizeClass = match($size) {
    'sm' => 'max-w-sm',
    'lg' => 'max-w-3xl',
    'xl' => 'max-w-5xl',
    default => 'max-w-xl',
};
@endphp

<dialog id="{{ $id }}" class="modal">
    <div class="modal-box {{ $sizeClass }}">
        @if($title)
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-lg">{{ $title }}</h3>
                <form method="dialog">
                    <button class="btn btn-ghost btn-sm btn-circle">
                        <x-icon name="x" class="w-4 h-4" />
                    </button>
                </form>
            </div>
        @endif

        {{ $slot }}

        @isset($footer)
            <div class="modal-action mt-4">
                {{ $footer }}
            </div>
        @endisset
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>
