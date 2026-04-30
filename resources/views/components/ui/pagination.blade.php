@if($paginator->hasPages())
<div class="flex flex-wrap items-center justify-between gap-4 mt-4">

    {{-- Info résultats --}}
    <p class="text-sm text-base-content/60">
        Affichage de
        <span class="font-medium">{{ $paginator->firstItem() }}</span>
        à
        <span class="font-medium">{{ $paginator->lastItem() }}</span>
        sur
        <span class="font-medium">{{ $paginator->total() }}</span>
        résultats
    </p>

    {{-- Pages --}}
    <div class="join">
        {{-- Précédent --}}
        @if($paginator->onFirstPage())
            <button class="join-item btn btn-sm btn-disabled" aria-disabled="true">
                <x-icon name="chevron-left" class="w-4 h-4" />
            </button>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="join-item btn btn-sm">
                <x-icon name="chevron-left" class="w-4 h-4" />
            </a>
        @endif

        {{-- Numéros de pages --}}
        @foreach($elements as $element)
            @if(is_string($element))
                <button class="join-item btn btn-sm btn-disabled">{{ $element }}</button>
            @endif

            @if(is_array($element))
                @foreach($element as $page => $url)
                    @if($page == $paginator->currentPage())
                        <button class="join-item btn btn-sm btn-active btn-primary">{{ $page }}</button>
                    @else
                        <a href="{{ $url }}" class="join-item btn btn-sm">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Suivant --}}
        @if($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="join-item btn btn-sm">
                <x-icon name="chevron-right" class="w-4 h-4" />
            </a>
        @else
            <button class="join-item btn btn-sm btn-disabled" aria-disabled="true">
                <x-icon name="chevron-right" class="w-4 h-4" />
            </button>
        @endif
    </div>
</div>
@endif
