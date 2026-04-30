@extends('layouts.admin')
@section('content')

<div class="space-y-6">

    {{-- En-tête --}}
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold">Feature Flags</h1>
            <p class="text-base-content/60 text-sm mt-1">
                Activez ou désactivez les modules. Les changements sont immédiats et audités.
            </p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('feature-flags.audit') }}" class="btn btn-ghost btn-sm gap-2">
                <x-icon name="history" class="w-4 h-4" />
                Historique
            </a>
            <form action="{{ route('feature-flags.sync') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline btn-sm gap-2">
                    <x-icon name="refresh" class="w-4 h-4" />
                    Sync config
                </button>
            </form>
        </div>
    </div>

    @if(session('status'))
        <x-ui.alert variant="success" dismissible>{{ session('status') }}</x-ui.alert>
    @endif

    @foreach($flags as $group => $items)
    <div class="card bg-base-100 shadow-sm">
        <div class="card-body p-0">
            <div class="px-6 py-4 border-b border-base-200 flex items-center gap-2">
                <x-icon name="layers-intersect" class="w-4 h-4 text-primary" />
                <h2 class="font-semibold">{{ $group }}</h2>
                <x-ui.badge variant="ghost" size="xs">{{ $items->count() }}</x-ui.badge>
            </div>

            <div class="divide-y divide-base-200">
                @foreach($items as $flag)
                <div class="flex items-center gap-4 px-6 py-4">
                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center flex-wrap gap-2">
                            <span class="font-medium text-sm">{{ $flag['label'] }}</span>
                            <code class="badge badge-ghost badge-xs">{{ $flag['key'] }}</code>
                            @unless($flag['in_db'])
                                <x-ui.badge variant="warning" size="xs">config seulement</x-ui.badge>
                            @endunless
                        </div>
                        @if($flag['description'])
                            <p class="text-base-content/50 text-xs mt-0.5">{{ $flag['description'] }}</p>
                        @endif
                        @if($flag['last_changed_by'] ?? false)
                            <p class="text-base-content/40 text-xs mt-1 flex items-center gap-1">
                                <x-icon name="clock" class="w-3 h-3" />
                                Modifié par {{ $flag['last_changed_by'] }}
                                {{ $flag['last_changed_at']?->diffForHumans() }}
                            </p>
                        @endif
                    </div>

                    {{-- Toggle Livewire (temps réel, sans rechargement) --}}
                    @livewire('feature-flag-toggle', ['featureKey' => $flag['key']], key($flag['key']))
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endforeach

</div>
@endsection
