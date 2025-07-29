<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Failed;
use Illuminate\Support\Facades\Log;

class LogFailedLogin
{
    public function handle(Failed $event)
    {
        $email = $event->credentials['email'] ?? 'inconnu';
        Log::warning("Connexion échouée pour l'email : {$email}");
    }
}
