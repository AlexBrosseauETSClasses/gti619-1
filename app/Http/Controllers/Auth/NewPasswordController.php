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
use App\Models\SecuritySetting;
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
        // Validation
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Utilisateur non trouvé.']);
        }

        $settings = SecuritySetting::first(); // suppose qu’il n’y a qu’un seul enregistrement

        //Vérifier la complexité du mot de passe
        if ($settings && $settings->enforce_complexity) {
            if (
                strlen($request->password) < $settings->min_length ||
                !preg_match('/[A-Z]/', $request->password) ||
                !preg_match('/[a-z]/', $request->password) ||
                !preg_match('/[0-9]/', $request->password)
            ) {
                return back()->withErrors(['password' => 'Le mot de passe ne respecte pas les critères de complexité.']);
            }
        }

        //Empêcher la réutilisation des anciens mots de passe
        if ($settings && $settings->password_history_count > 0) {
            $previousPasswords = PreviousPassword::where('user_id', $user->id)
                ->latest()->take($settings->password_history_count)->get();

            foreach ($previousPasswords as $previous) {
                if (Hash::check($request->password, $previous->password)) {
                    return back()->withErrors(['password' => 'Ce mot de passe a déjà été utilisé. Veuillez en choisir un autre.']);
                }
            }
        }

        //Sauvegarder l’ancien mot de passe
        PreviousPassword::create([
            'user_id' => $user->id,
            'password' => $user->password,
        ]);

        //Mettre à jour le mot de passe
        $user->password = Hash::make($request->password);
        $user->save();

        Log::info("Mot de passe modifié pour l'utilisateur ID={$user->id}");

        return redirect()->route('login')->with('status', 'Mot de passe réinitialisé.');
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
