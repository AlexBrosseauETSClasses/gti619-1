<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use App\Models\PreviousPassword;
use App\Models\User;
use Illuminate\Validation\Rules\Password as PasswordRule;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
   public function store(Request $request)
    {
       $request->validate([
        'token' => ['required'],
        'email' => ['required', 'email'],
        'password' => [
            'required',
            'confirmed',
            PasswordRule::min(8)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised(),
        ],
    ]);

        // Récupérer l'utilisateur avec l'email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Utilisateur non trouvé.']);
        }

        // Vérifier si le nouveau mot de passe a déjà été utilisé
        $previousPasswords = PreviousPassword::where('user_id', $user->id)->get();

        foreach ($previousPasswords as $previous) {
            if (Hash::check($request->password, $previous->password)) {
                return back()->withErrors([
                    'password' => 'Ce mot de passe a déjà été utilisé. Veuillez en choisir un autre.'
                ]);
            }
        }
        Log::info("Mot de passe modifié pour l'utilisateur ID={$user->id}, Email={$user->email}");
        // Effectuer la réinitialisation
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                // Sauvegarder l'ancien mot de passe dans la table previous_passwords
                PreviousPassword::create([
                    'user_id' => $user->id,
                    'password' => $user->password, // ancien hash
                ]);

                $user->password = Hash::make($password);
                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }
    public function update(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:12', 'confirmed', 
                'regex:/[a-z]/',   
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*?&]/'
            ],
        ]);

        // old mdp
        if ($this->isPreviousPassword($request->user(), $request->password)) {
            return back()->withErrors(['password' => 'Ce mot de passe a déjà été utilisé.']);
        }

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('status', 'Mot de passe mis à jour.');
    }
    protected function isPreviousPassword($user, $newPassword)
    {
        $history = $user->previousPasswords()->orderBy('created_at', 'desc')->take(5)->get();
        foreach ($history as $old) {
            if (Hash::check($newPassword, $old->password)) {
                return true;
            }
        }
        return false;
    }
}
