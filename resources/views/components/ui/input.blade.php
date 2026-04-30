@props([
    'label'       => null,
    'error'       => null,
    'hint'        => null,
    'required'    => false,
    'icon'        => null,
    'type'        => 'text',
])

@php
$inputClass = 'input input-bordered w-full '
    . ($error ? 'input-error ' : '')
    . ($icon  ? 'pl-10 '       : '');
@endphp

<div class="form-control w-full">
    @if($label)
        <label class="label">
            <span class="label-text font-medium">
                {{ $label }}
                @if($required)<span class="text-error ml-0.5">*</span>@endif
            </span>
        </label>
    @endif

    <div class="relative">
        @if($icon)
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-base-content/40">
                <x-icon :name="$icon" class="w-4 h-4" />
            </span>
        @endif

        <input
            type="{{ $type }}"
            {{ $attributes->merge(['class' => $inputClass]) }}
        />
    </div>

    @if($error)
        <label class="label">
            <span class="label-text-alt text-error">{{ $error }}</span>
        </label>
    @elseif($hint)
        <label class="label">
            <span class="label-text-alt text-base-content/60">{{ $hint }}</span>
        </label>
    @endif
</div>
