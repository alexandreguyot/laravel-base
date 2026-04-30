<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PragmaRX\Google2FALaravel\Support\Authenticator;

class TwoFactorController extends Controller
{
    protected function google2fa()
    {
        return app('pragmarx.google2fa');
    }

    /* ── Setup ──────────────────────────────────────────────── */

    public function setup(Request $request)
    {
        $user   = $request->user();
        $google = $this->google2fa();

        if (! $user->two_factor_secret) {
            $user->update(['two_factor_secret' => encrypt($google->generateSecretKey())]);
        }

        $secret = decrypt($user->two_factor_secret);
        $qrUrl  = $google->getQRCodeUrl(config('app.name'), $user->email, $secret);
        $qrSvg  = \BaconQrCode\Renderer\Image\SvgImageBackEnd::class;

        $renderer = new \BaconQrCode\Renderer\ImageRenderer(
            new \BaconQrCode\Renderer\RendererStyle\RendererStyle(200),
            new \BaconQrCode\Renderer\Image\SvgImageBackEnd()
        );
        $qrCode = (new \BaconQrCode\Writer($renderer))->writeString($qrUrl);

        return view('auth.two-factor.setup', compact('user', 'qrCode', 'secret'));
    }

    public function enable(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $user = $request->user();

        if (! $user->two_factor_secret) {
            return redirect()->route('2fa.setup')
                ->withErrors(['code' => 'Vous devez d\'abord scanner le QR code.']);
        }

        $google = $this->google2fa();
        $secret = decrypt($user->two_factor_secret);

        if (! $google->verifyKey($secret, $request->code)) {
            return back()->withErrors(['code' => 'Code invalide. Réessayez.']);
        }

        $user->update([
            'two_factor_enabled'      => true,
            'two_factor_confirmed_at' => now(),
        ]);

        session(['2fa_verified' => true]);

        return redirect()->route('profile.show')->with('success', '2FA activé avec succès.');
    }

    public function disable(Request $request)
    {
        $request->user()->update([
            'two_factor_secret'       => null,
            'two_factor_enabled'      => false,
            'two_factor_confirmed_at' => null,
        ]);

        session()->forget('2fa_verified');

        return redirect()->route('profile.show')->with('success', '2FA désactivé.');
    }

    /* ── Challenge (vérification à la connexion) ────────────── */

    public function challenge()
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        return view('auth.two-factor.challenge');
    }

    public function verify(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $user   = $request->user();
        $google = $this->google2fa();
        $secret = decrypt($user->two_factor_secret);

        if (! $google->verifyKey($secret, $request->code)) {
            return back()->withErrors(['code' => 'Code invalide.']);
        }

        session(['2fa_verified' => true]);

        return redirect()->intended(route('home'));
    }
}
