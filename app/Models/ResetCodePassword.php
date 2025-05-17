<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResetCodePassword extends Model
{
      protected $fillable = [
        'phone',
        'email',
        'verification_code',
        'expires_at',
        'attempts',
        'blocked',
        'blocked_until',
        'account_locked',
        'account_locked_until',
    ];
    protected $casts = [
        'expires_at' => 'datetime',
        'blocked_until' => 'datetime',
        'account_locked_until' => 'datetime',
    ];
}
