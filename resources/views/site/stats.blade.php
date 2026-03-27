@extends('layouts.app')

@section('content')
    {{-- Navigation --}}
    <div class="max-w-7xl mx-auto px-4 pt-6" x-data>
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-white">Daily Dunk</h2>
            <div class="flex items-center gap-3">
                <a href="{{ route('get.home') }}" class="bg-gray-700 hover:bg-gray-600 text-gray-300 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    Jouer
                </a>
                <a href="{{ route('get.stats') }}" class="bg-orange-500 text-white px-4 py-2 rounded-lg text-sm font-medium">
                    Stats
                </a>
                @auth
                    <div class="flex items-center gap-2">
                        @if(auth()->user()->avatar)
                            <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full" title="{{ auth()->user()->name }}">
                        @else
                            <span class="w-8 h-8 rounded-full bg-orange-500 flex items-center justify-center text-white text-xs font-bold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        @endif
                        <form method="POST" action="{{ route('auth.logout') }}">
                            @csrf
                            <button type="submit" class="text-gray-400 hover:text-white text-sm transition-colors">Déco</button>
                        </form>
                    </div>
                @else
                    <button @click="window.dispatchEvent(new Event('open-auth'))" class="bg-white hover:bg-gray-100 text-gray-800 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        Connexion
                    </button>
                @endauth
            </div>
        </div>
    </div>

    <livewire:nba-last-night-stats :date="$date ?? null" />
@endsection
