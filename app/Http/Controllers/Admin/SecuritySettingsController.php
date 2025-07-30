<?php

namespace App\Http\Controllers\Admin;

use App\Models\SecuritySetting;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SecuritySettingsController extends Controller
{
    public function edit()
    {
        $settings = SecuritySetting::firstOrCreate([]);
        return view('admin.security-settings', compact('settings'));
    }

    public function update(Request $request)
    {
        try {
            $request->validate([
                'min_password_length' => 'required|integer|min:1',
                'require_uppercase' => 'nullable|in:on',
                'require_numbers' => 'nullable|in:on',
                'require_special_chars' => 'nullable|in:on',
                'password_history_count' => 'required|integer|min:0',
                'max_login_attempts' => 'required|integer|min:1',
            ]);


            $settings = SecuritySetting::firstOrCreate([]);

            // Met à jour chaque truc
            $settings->min_password_length = $request->min_password_length;
            $settings->require_uppercase = $request->has('require_uppercase');
            $settings->require_numbers = $request->has('require_numbers');
            $settings->require_special_chars = $request->has('require_special_chars');
            $settings->password_history_count = $request->password_history_count;
            $settings->max_login_attempts = $request->max_login_attempts;

            $settings->save();

            return redirect()->route('security.edit')
                            ->with('success', 'Paramètres de sécurité mis à jour avec succès.');
        } catch (\Exception $e) {
            \Log::error('Erreur mise à jour paramètres sécurité : ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }

}
