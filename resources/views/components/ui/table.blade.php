@props([
    'headers'   => [],     // array de strings ou ['label' => 'Nom', 'sortable' => true, 'key' => 'name']
    'striped'   => false,
    'hover'     => true,
    'compact'   => false,
    'bordered'  => false,
    'pinActions'=> true,   // épingle la colonne actions à droite
])

@php
$tableClass = 'table w-full '
    . ($striped  ? 'table-zebra '  : '')
    . ($hover    ? 'table-hover '  : '')
    . ($compact  ? 'table-xs '     : '')
    . ($bordered ? 'table-bordered ': '');
@endphp

<div class="overflow-x-auto rounded-box">
    <table {{ $attributes->merge(['class' => $tableClass]) }}>
        @if(!empty($headers))
            <thead>
                <tr>
                    @foreach($headers as $header)
                        @if(is_array($header))
                            <th class="{{ $header['class'] ?? '' }}">
                                @if($header['sortable'] ?? false)
                                    <button type="button" wire:click="sortBy('{{ $header['key'] }}')"
                                        class="flex items-center gap-1 hover:text-primary">
                                        {{ $header['label'] }}
                                        <x-icon name="arrows-sort" class="w-3 h-3 opacity-50" />
                                    </button>
                                @else
                                    {{ $header['label'] ?? $header }}
                                @endif
                            </th>
                        @else
                            <th>{{ $header }}</th>
                        @endif
                    @endforeach
                </tr>
            </thead>
        @endif

        <tbody>
            {{ $slot }}
        </tbody>

        @isset($footer)
            <tfoot>{{ $footer }}</tfoot>
        @endisset
    </table>
</div>
