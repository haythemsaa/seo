<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FA\Google2FA;
use Inertia\Inertia;

class TwoFactorController extends Controller
{
    protected $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA();
    }

    /**
     * Show 2FA setup page.
     */
    public function show()
    {
        $user = Auth::user();

        if ($user->hasTwoFactorEnabled()) {
            return redirect()->route('dashboard');
        }

        $secret = $this->google2fa->generateSecretKey();

        $qrCodeUrl = $this->google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secret
        );

        return Inertia::render('Auth/TwoFactor/Setup', [
            'secret' => $secret,
            'qrCodeUrl' => $qrCodeUrl,
        ]);
    }

    /**
     * Enable 2FA for user.
     */
    public function enable(Request $request)
    {
        $request->validate([
            'secret' => 'required|string',
            'code' => 'required|string|size:6',
        ]);

        $user = Auth::user();

        $valid = $this->google2fa->verifyKey($request->secret, $request->code);

        if (!$valid) {
            return back()->withErrors([
                'code' => 'Le code de vérification est invalide.',
            ]);
        }

        $user->update([
            'two_factor_secret' => encrypt($request->secret),
        ]);

        return redirect()->route('dashboard')->with('success', 'Authentification à deux facteurs activée avec succès.');
    }

    /**
     * Disable 2FA for user.
     */
    public function disable(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password',
        ]);

        $user = Auth::user();
        $user->update(['two_factor_secret' => null]);

        return back()->with('success', 'Authentification à deux facteurs désactivée.');
    }

    /**
     * Show 2FA verification page.
     */
    public function showVerify()
    {
        if (!session('2fa_user_id')) {
            return redirect()->route('login');
        }

        return Inertia::render('Auth/TwoFactor/Verify');
    }

    /**
     * Verify 2FA code and complete login.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $userId = session('2fa_user_id');
        if (!$userId) {
            return redirect()->route('login');
        }

        $user = \App\Models\User::findOrFail($userId);

        $secret = decrypt($user->two_factor_secret);
        $valid = $this->google2fa->verifyKey($secret, $request->code);

        if (!$valid) {
            return back()->withErrors([
                'code' => 'Le code de vérification est invalide.',
            ]);
        }

        session()->forget('2fa_user_id');
        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
