@if($library === 'lucide')
    {{-- Lucide : rendu côté JS via createIcons() --}}
    <i data-lucide="{{ $name }}" class="{{ $class }}"></i>
@elseif($library === 'fontawesome')
    {{-- Font Awesome 5/6 -- le nom doit inclure le préfixe (fas fa-home) --}}
    <i class="{{ $name }} {{ $class }}"></i>
@elseif($library === 'heroicons')
    {{-- Heroicons via blade-heroicons package --}}
    @svg('heroicon-o-'.$name, $class)
@else
    {{-- Fallback : lucide --}}
    <i data-lucide="{{ $name }}" class="{{ $class }}"></i>
@endif
