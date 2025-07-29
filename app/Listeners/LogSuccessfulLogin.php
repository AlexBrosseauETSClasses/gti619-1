<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Log;

class LogSuccessfulLogin
{
    public function handle(Login $event)
    {
        Log::info("Connexion réussie pour l'utilisateur ID={$event->user->id}, Email={$event->user->email}");
    }
}
