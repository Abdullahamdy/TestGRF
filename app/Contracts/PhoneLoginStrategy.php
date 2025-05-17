<?php

namespace App\Contracts;

use App\Models\User;

class PhoneLoginStrategy implements LoginStrategyInterface
{
    public function findUser(array $credentials): ?User
    {
        return isset($credentials['phone'])
            ? User::where('status',1)->where('phone', $credentials['phone'])->first()
            : null;
    }
}
