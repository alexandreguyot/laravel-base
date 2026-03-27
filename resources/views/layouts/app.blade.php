<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ config('app.name', 'Daily Dunk') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="text-gray-700 bg-gray-800 antialiased">
    <main>
        @yield('content')
    </main>

    {{-- Auth Modal --}}
    <div
        x-data="{
            open: {{ $errors->any() ? 'true' : 'false' }},
            tab: 'login'
        }"
        @open-auth.window="open = true"
    >
        {{-- Backdrop --}}
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="open = false"
            class="fixed inset-0 bg-black/60 z-40"
            style="display:none"
        ></div>

        {{-- Modal --}}
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="fixed inset-0 z-50 flex items-center justify-center p-4"
            style="display:none"
        >
            <div class="bg-gray-800 border border-gray-600 rounded-2xl shadow-2xl w-full max-w-sm p-6" @click.stop>

                {{-- Close --}}
                <div class="flex justify-between items-center mb-5">
                    <h2 class="text-white font-bold text-xl">Daily Dunk</h2>
                    <button @click="open = false" class="text-gray-400 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Google --}}
                <a href="{{ route('auth.google') }}"
                   class="flex items-center justify-center gap-3 w-full bg-white hover:bg-gray-50 text-gray-800 font-medium py-2.5 rounded-xl transition-colors mb-4">
                    <svg class="w-5 h-5" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    Continuer avec Google
                </a>

                {{-- Separator --}}
                <div class="flex items-center gap-3 mb-4">
                    <div class="flex-1 h-px bg-gray-600"></div>
                    <span class="text-gray-500 text-xs">ou</span>
                    <div class="flex-1 h-px bg-gray-600"></div>
                </div>

                {{-- Tabs --}}
                <div class="flex bg-gray-700 rounded-lg p-1 mb-4">
                    <button @click="tab = 'login'"
                        :class="tab === 'login' ? 'bg-gray-800 text-white shadow' : 'text-gray-400 hover:text-white'"
                        class="flex-1 py-1.5 text-sm font-medium rounded-md transition-all">
                        Connexion
                    </button>
                    <button @click="tab = 'register'"
                        :class="tab === 'register' ? 'bg-gray-800 text-white shadow' : 'text-gray-400 hover:text-white'"
                        class="flex-1 py-1.5 text-sm font-medium rounded-md transition-all">
                        Inscription
                    </button>
                </div>

                {{-- Errors --}}
                @if ($errors->any())
                    <div class="bg-red-900/50 border border-red-500/50 text-red-300 text-xs rounded-lg px-3 py-2 mb-4">
                        {{ $errors->first() }}
                    </div>
                @endif

                {{-- Login form --}}
                <form x-show="tab === 'login'" action="{{ route('auth.login') }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <input type="email" name="email" placeholder="Email"
                            value="{{ old('email') }}"
                            class="w-full bg-gray-700 border border-gray-600 text-white text-sm rounded-xl px-3 py-2.5 outline-none focus:border-orange-400 transition-colors placeholder-gray-500">
                    </div>
                    <div>
                        <input type="password" name="password" placeholder="Mot de passe"
                            class="w-full bg-gray-700 border border-gray-600 text-white text-sm rounded-xl px-3 py-2.5 outline-none focus:border-orange-400 transition-colors placeholder-gray-500">
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="remember" id="remember" class="rounded">
                        <label for="remember" class="text-gray-400 text-xs">Se souvenir de moi</label>
                    </div>
                    <button type="submit"
                        class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2.5 rounded-xl transition-colors text-sm">
                        Se connecter
                    </button>
                </form>

                {{-- Register form --}}
                <form x-show="tab === 'register'" action="{{ route('auth.register') }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <input type="text" name="name" placeholder="Nom d'affichage"
                            value="{{ old('name') }}"
                            class="w-full bg-gray-700 border border-gray-600 text-white text-sm rounded-xl px-3 py-2.5 outline-none focus:border-orange-400 transition-colors placeholder-gray-500">
                    </div>
                    <div>
                        <input type="email" name="email" placeholder="Email"
                            value="{{ old('email') }}"
                            class="w-full bg-gray-700 border border-gray-600 text-white text-sm rounded-xl px-3 py-2.5 outline-none focus:border-orange-400 transition-colors placeholder-gray-500">
                    </div>
                    <div>
                        <input type="password" name="password" placeholder="Mot de passe (8 car. min.)"
                            class="w-full bg-gray-700 border border-gray-600 text-white text-sm rounded-xl px-3 py-2.5 outline-none focus:border-orange-400 transition-colors placeholder-gray-500">
                    </div>
                    <div>
                        <input type="password" name="password_confirmation" placeholder="Confirmer le mot de passe"
                            class="w-full bg-gray-700 border border-gray-600 text-white text-sm rounded-xl px-3 py-2.5 outline-none focus:border-orange-400 transition-colors placeholder-gray-500">
                    </div>
                    <button type="submit"
                        class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2.5 rounded-xl transition-colors text-sm">
                        Créer mon compte
                    </button>
                </form>

            </div>
        </div>
    </div>

    @livewireScripts
</body>

</html>
