@props([
    'label'       => null,
    'error'       => null,
    'hint'        => null,
    'required'    => false,
    'placeholder' => 'Sélectionner...',
    'options'     => [],   // ['value' => 'label'] ou collection
    'searchable'  => false,
    'multiple'    => false,
])

@php
$selectClass = 'select select-bordered w-full '
    . ($error ? 'select-error ' : '');
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

    @if($searchable)
        {{-- Select avec recherche — Alpine.js, sans jQuery --}}
        <div
            x-data="{
                open: false,
                search: '',
                selected: @entangle($attributes->wire('model')->value() ?? ''),
                options: {{ json_encode(is_array($options) ? $options : $options->toArray()) }},
                get filtered() {
                    if (!this.search) return this.options
                    return Object.fromEntries(
                        Object.entries(this.options).filter(([v, l]) =>
                            l.toString().toLowerCase().includes(this.search.toLowerCase())
                        )
                    )
                },
                getLabel(val) {
                    return this.options[val] ?? '{{ $placeholder }}'
                }
            }"
            class="relative"
            @click.outside="open = false"
        >
            <button type="button"
                @click="open = !open"
                class="{{ $selectClass }} text-left flex items-center justify-between">
                <span x-text="selected ? getLabel(selected) : '{{ $placeholder }}'"
                    :class="{ 'text-base-content/40': !selected }"></span>
                <x-icon name="arrows-sort" class="w-4 h-4 opacity-40 shrink-0" />
            </button>

            <div x-show="open" x-transition
                class="absolute z-50 w-full mt-1 bg-base-100 border border-base-300 rounded-box shadow-lg">
                <div class="p-2 border-b border-base-200">
                    <input
                        x-model="search"
                        type="text"
                        placeholder="Rechercher..."
                        class="input input-sm input-bordered w-full"
                        @click.stop
                    />
                </div>
                <ul class="max-h-48 overflow-y-auto p-1">
                    <template x-for="[value, label] in Object.entries(filtered)" :key="value">
                        <li>
                            <button type="button"
                                @click="selected = value; open = false; search = ''"
                                class="btn btn-ghost btn-sm btn-block justify-start font-normal"
                                :class="{ 'btn-active': selected == value }"
                                x-text="label">
                            </button>
                        </li>
                    </template>
                    <template x-if="Object.keys(filtered).length === 0">
                        <li class="text-center py-3 text-sm text-base-content/40">Aucun résultat</li>
                    </template>
                </ul>
            </div>

            {{-- Input caché pour la valeur --}}
            <input type="hidden" :value="selected" {{ $attributes->whereDoesntStartWith(['wire:', 'x-']) }} />
        </div>

    @else
        {{-- Select natif DaisyUI --}}
        <select {{ $attributes->merge(['class' => $selectClass]) }}
            @if($multiple) multiple @endif>
            @if($placeholder && !$multiple)
                <option value="" disabled selected>{{ $placeholder }}</option>
            @endif
            @foreach($options as $value => $optLabel)
                <option value="{{ $value }}">{{ $optLabel }}</option>
            @endforeach
            {{ $slot }}
        </select>
    @endif

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
