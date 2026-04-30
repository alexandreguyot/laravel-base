@extends('layouts.admin')
@section('content')

<div class="max-w-lg mx-auto space-y-6">
    <x-ui.breadcrumb :items="[
        ['label' => trans('global.dashboard'), 'href' => route('home'), 'icon' => 'layout-dashboard'],
        ['label' => 'Mon profil', 'href' => route('profile.show')],
        ['label' => 'Activer le 2FA'],
    ]" />

    <x-ui.card>
        <div class="p-6 space-y-6">
            <div class="text-center">
                <h2 class="text-2xl font-bold mb-2">Authentification à deux facteurs</h2>
                <p class="text-base-content/60">Scannez le QR code avec Google Authenticator, Authy ou une app compatible.</p>
            </div>

            <div class="flex justify-center">
                <div class="p-4 bg-white rounded-xl shadow-sm border border-base-200">
                    {!! $qrCode !!}
                </div>
            </div>

            <div class="bg-base-200 rounded-xl p-4 text-center">
                <p class="text-sm text-base-content/60 mb-1">Ou entrez ce code manuellement :</p>
                <code class="text-lg font-mono font-bold tracking-widest select-all">{{ $secret }}</code>
            </div>

            <form action="{{ route('2fa.enable') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="label"><span class="label-text font-medium">Code de vérification</span></label>
                    <input type="text" name="code" inputmode="numeric" maxlength="6"
                           class="input input-bordered w-full text-center text-2xl tracking-[0.5em] font-mono"
                           placeholder="000000" autofocus autocomplete="one-time-code" />
                    @error('code')
                        <p class="text-error text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary w-full">Activer le 2FA</button>
            </form>
        </div>
    </x-ui.card>
</div>

@endsection
