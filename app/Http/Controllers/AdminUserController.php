<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function create()
    {
        return view('admin.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // 👇 On stocke dans $user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 🔐 Ajoute le rôle Spatie
        $user->assignRole($request->role);

        // (Optionnel) Enregistre aussi le rôle dans la colonne "role"
        $user->update(['role' => $request->role]);

        return redirect()->route('admin.register')->with('success', 'Utilisateur ajouté avec succès.');
    }
}
