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
    public function create(Request $request)
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    public function store(Request $request)
    {
        $settings = SecuritySetting::first();

        $rules = [
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed'],
        ];

        if ($settings) {
            $passwordRules = [PasswordRule::min($settings->min_password_length)];
            $rules['password'] = array_merge($rules['password'], $passwordRules);
        }

        $request->validate($rules);

        $errors = [];

        if ($settings->require_uppercase && !preg_match('/[A-Z]/', $request->password)) {
            $errors[] = 'Le mot de passe doit contenir au moins une lettre majuscule.';
        }

        if ($settings->require_numbers && !preg_match('/[0-9]/', $request->password)) {
            $errors[] = 'Le mot de passe doit contenir au moins un chiffre.';
        }

        if ($settings->require_special_chars && !preg_match('/[\W_]/', $request->password)) {
            $errors[] = 'Le mot de passe doit contenir au moins un caractère spécial.';
        }

        if (!empty($errors)) {
            return back()->withErrors(['password' => implode(' ', $errors)]);
        }

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'Utilisateur non trouvé.']);
        }

        if ($settings && $settings->password_history_count > 0) {
            $previousPasswords = PreviousPassword::where('user_id', $user->id)
                ->latest()
                ->take($settings->password_history_count)
                ->get();

            foreach ($previousPasswords as $previous) {
                if (Hash::check($request->password, $previous->password)) {
                    return back()->withErrors(['password' => 'Ce mot de passe a déjà été utilisé. Veuillez en choisir un autre.']);
                }
            }
        }

        PreviousPassword::create([
            'user_id' => $user->id,
            'password' => $user->password,
        ]);

        $user->password = Hash::make($request->password);
        $user->save();
        $user = User::where('email', $request->email)->first();

        if ($user) {
            $user->is_locked = false; //Débloquer le compte
            $user->login_attempts = 0; //Réinitialiser les tentatives
            $user->save();
        }

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
