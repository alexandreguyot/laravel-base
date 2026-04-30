<div class="space-y-4">

    {{-- Barre d'outils --}}
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-2 flex-wrap">
            {{-- Par page --}}
            <select wire:model.live="perPage" class="select select-bordered select-sm w-24">
                @foreach($paginationOptions as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>

            {{-- Supprimer la sélection --}}
            @can('product_delete')
                <button type="button"
                    wire:click="confirm('deleteSelected')"
                    wire:loading.attr="disabled"
                    {{ $this->selectedCount ? '' : 'disabled' }}
                    class="btn btn-error btn-sm gap-2 disabled:opacity-40">
                    <x-icon name="trash" class="w-4 h-4" />
                    Supprimer ({{ $this->selectedCount }})
                </button>
            @endcan

            {{-- Exports --}}
            @feature('excel_export')
            @if(file_exists(app_path('Livewire/ExcelExport.php')))
                <div class="dropdown">
                    <button tabindex="0" class="btn btn-ghost btn-sm gap-2">
                        <x-icon name="download" class="w-4 h-4" />
                        Exporter
                        <x-icon name="chevron-down" class="w-3 h-3" />
                    </button>
                    <ul tabindex="0" class="dropdown-content z-10 menu menu-sm bg-base-100 rounded-box shadow w-36 p-1">
                        <li><a><livewire:excel-export model="Product" format="csv" /></a></li>
                        <li><a><livewire:excel-export model="Product" format="xlsx" /></a></li>
                        <li><a><livewire:excel-export model="Product" format="pdf" /></a></li>
                    </ul>
                </div>
            @endif
            @endfeature
        </div>

        {{-- Recherche --}}
        <div class="relative">
            <x-icon name="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-base-content/40" />
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="{{ trans('global.search') }}..."
                class="input input-bordered input-sm pl-9 w-56"
            />
        </div>
    </div>

    {{-- Indicateur chargement --}}
    <div wire:loading.delay class="flex items-center gap-2 text-sm text-base-content/60">
        <span class="loading loading-spinner loading-xs"></span>
        Chargement...
    </div>

    {{-- Tableau --}}
    <x-ui.table :headers="[
        ['label' => trans('cruds.product.fields.id'),          'key' => 'id',          'sortable' => true],
        ['label' => trans('cruds.product.fields.name'),        'key' => 'name',        'sortable' => true],
        ['label' => trans('cruds.product.fields.description'), 'key' => 'description', 'sortable' => true],
        ['label' => trans('cruds.product.fields.price'),       'key' => 'price',       'sortable' => true],
        ['label' => trans('cruds.product.fields.category')],
        ['label' => trans('cruds.product.fields.tag')],
        ['label' => trans('cruds.product.fields.photo')],
        ['label' => ''],
    ]" hover>
        @forelse($products as $product)
        <tr>
            <td class="w-8">
                <input type="checkbox" value="{{ $product->id }}" wire:model.live="selected" class="checkbox checkbox-sm" />
            </td>
            <td class="text-sm font-mono text-base-content/50">#{{ $product->id }}</td>
            <td class="font-medium">{{ $product->name }}</td>
            <td class="text-sm text-base-content/70 max-w-xs truncate">{{ $product->description }}</td>
            <td class="font-medium">{{ number_format($product->price, 2) }} €</td>
            <td>
                @foreach($product->category as $entry)
                    <x-ui.badge variant="ghost" size="xs">{{ $entry->name }}</x-ui.badge>
                @endforeach
            </td>
            <td>
                @foreach($product->tag as $entry)
                    <x-ui.badge size="xs">{{ $entry->name }}</x-ui.badge>
                @endforeach
            </td>
            <td>
                @foreach($product->photo as $entry)
                    <a href="{{ $entry['url'] }}" target="_blank" class="link-photo">
                        <img src="{{ $entry['thumbnail'] }}" alt="{{ $entry['name'] }}"
                            class="w-10 h-10 object-cover rounded-btn inline-block" />
                    </a>
                @endforeach
            </td>
            <td>
                <div class="flex items-center justify-end gap-1">
                    @can('product_show')
                        <a href="{{ route('products.show', $product) }}"
                            class="btn btn-ghost btn-xs tooltip" data-tip="{{ trans('global.view') }}">
                            <x-icon name="eye" class="w-4 h-4" />
                        </a>
                    @endcan
                    @can('product_edit')
                        <a href="{{ route('products.edit', $product) }}"
                            class="btn btn-ghost btn-xs tooltip" data-tip="{{ trans('global.edit') }}">
                            <x-icon name="pencil" class="w-4 h-4" />
                        </a>
                    @endcan
                    @can('product_delete')
                        <button type="button"
                            wire:click="confirm('delete', {{ $product->id }})"
                            class="btn btn-ghost btn-xs text-error tooltip" data-tip="{{ trans('global.delete') }}">
                            <x-icon name="trash" class="w-4 h-4" />
                        </button>
                    @endcan
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="9" class="text-center py-12 text-base-content/40">
                <x-icon name="package" class="w-10 h-10 mx-auto mb-3" />
                <p>Aucun produit trouvé.</p>
            </td>
        </tr>
        @endforelse
    </x-ui.table>

    {{-- Footer --}}
    <div class="flex items-center justify-between gap-4">
        @if($this->selectedCount)
            <p class="text-sm text-base-content/60">
                <span class="font-medium text-base-content">{{ $this->selectedCount }}</span>
                élément(s) sélectionné(s)
            </p>
        @else
            <div></div>
        @endif

        {{ $products->links() }}
    </div>

</div>

@push('scripts')
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('confirm', (e) => {
            if (!confirm("{{ trans('global.areYouSure') }}")) return
            Livewire.find(e.component).call(e.callback, ...e.argv)
        })
    })
</script>
@endpush
