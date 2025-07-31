<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\Admin\SecuritySettingsController;
use App\Models\SecuritySetting;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();
        $settings = SecuritySetting::first();

        if (!$user) {
            return back()->withErrors(['email' => 'Identifiants invalides.']);
        }

        // Vérifie si le compte est bloqué
        if ($user->is_locked) {
            return back()->withErrors(['email' => 'Ce compte est bloqué. Veuillez réinitialiser votre mot de passe.']);
        }

        if (!Hash::check($request->password, $user->password)) {
            $user->increment('login_attempts');

            if ($settings && $user->login_attempts >= $settings->max_login_attempts) {
                $user->is_locked = true;
                $user->save();

                // Envoie automatique de l’email de réinitialisation
                $status = Password::sendResetLink(['email' => $user->email]);

                return back()->withErrors([
                    'email' => 'Compte bloqué après plusieurs tentatives. Un lien de réinitialisation a été envoyé.',
                ]);
            }

            $user->save();
            return back()->withErrors(['email' => 'Identifiants invalides.']);
        }

        // Connexion réussie : reset des tentatives
        $user->login_attempts = 0;
        $user->save();

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
