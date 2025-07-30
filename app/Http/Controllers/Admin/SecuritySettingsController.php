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
        return view('admin.dashboard', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'min_password_length' => 'required|integer|min:6',
            'require_uppercase' => 'nullable|boolean',
            'require_numbers' => 'nullable|boolean',
            'require_special_chars' => 'nullable|boolean',
            'password_history_count' => 'required|integer|min:0',
        ]);

        $settings = SecuritySetting::firstOrCreate([]);

        $settings->min_password_length = $request->min_password_length;
        $settings->require_uppercase = $request->has('require_uppercase');
        $settings->require_numbers = $request->has('require_numbers');
        $settings->require_special_chars = $request->has('require_special_chars');
        $settings->password_history_count = $request->password_history_count;

        $settings->save();

        return redirect()->route('security.edit')->with('success', 'Paramètres mis à jour.');
    }
}
