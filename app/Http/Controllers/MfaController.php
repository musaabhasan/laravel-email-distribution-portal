<?php

namespace App\Http\Controllers;

use App\Services\Security\TotpService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MfaController extends Controller
{
    public function show(): View
    {
        return view('auth.mfa');
    }

    public function verify(Request $request, TotpService $totp): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $user = $request->user();

        if (! $user || ! $user->mfa_secret || ! $totp->verify($user->mfa_secret, $validated['code'])) {
            return back()->withErrors(['code' => 'The verification code is invalid.']);
        }

        $request->session()->put('mfa_verified_at', now());

        return redirect()->intended(route('dashboard'));
    }
}
