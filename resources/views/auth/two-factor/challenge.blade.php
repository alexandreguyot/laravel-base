@extends('layouts.app')
@section('content')

<div class="min-h-screen flex items-center justify-center bg-base-200 px-4">
    <div class="card bg-base-100 shadow-xl w-full max-w-sm">
        <div class="card-body space-y-6">
            <div class="text-center">
                <div class="text-5xl mb-3">🔐</div>
                <h2 class="text-2xl font-bold">Vérification 2FA</h2>
                <p class="text-base-content/60 text-sm mt-1">Entrez le code de votre application d'authentification.</p>
            </div>

            <form action="{{ route('2fa.verify') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <input type="text" name="code" inputmode="numeric" maxlength="6"
                           class="input input-bordered w-full text-center text-2xl tracking-[0.5em] font-mono"
                           placeholder="000000" autofocus autocomplete="one-time-code" />
                    @error('code')
                        <p class="text-error text-sm mt-1 text-center">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary w-full">Vérifier</button>
            </form>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-ghost btn-sm w-full text-base-content/50">
                    Se déconnecter
                </button>
            </form>
        </div>
    </div>
</div>

@endsection
