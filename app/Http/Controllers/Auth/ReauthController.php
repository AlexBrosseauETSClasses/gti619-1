<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class ReauthController extends Controller
{
    public function showForm()
    {
        return view('auth.reauth');
    }

    public function reauthenticate(Request $request)
    {
        $request->validate([
            'password' => ['required'],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Mot de passe incorrect.']);
        }
        session(['reauthenticated_at' => now()]);
        return redirect()->intended(); // va utiliser url.intended
    }

}
