<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SecuritySetting extends Model
{
    protected $fillable = [
        'min_password_length',
        'reuse_last_passwords_count',
        'max_login_attempts',
        'enforce_complexity',
    ];
}