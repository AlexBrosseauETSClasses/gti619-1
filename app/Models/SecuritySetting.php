<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SecuritySetting extends Model
{
    protected $fillable = [
        'min_password_length',
        'require_uppercase',
        'require_numbers',
        'require_special_chars',
        'password_history_count',
        'max_login_attempts',
    ];
}