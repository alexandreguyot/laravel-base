@props(['items' => []])
{{--
    Usage :
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'href' => route('home')],
        ['label' => 'Produits',  'href' => route('products.index')],
        ['label' => 'Créer'],
    ]" />
--}}

<div class="breadcrumbs text-sm mb-4">
    <ul>
        @foreach($items as $item)
            <li>
                @if(isset($item['href']))
                    <a href="{{ $item['href'] }}" class="hover:text-primary">
                        @if(isset($item['icon']))<x-icon :name="$item['icon']" class="w-4 h-4 mr-1" />@endif
                        {{ $item['label'] }}
                    </a>
                @else
                    @if(isset($item['icon']))<x-icon :name="$item['icon']" class="w-4 h-4 mr-1" />@endif
                    {{ $item['label'] }}
                @endif
            </li>
        @endforeach
    </ul>
</div>
