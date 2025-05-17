<?php

namespace App\Contracts;

use App\Models\User;

class UsernameLoginStrategy implements LoginStrategyInterface
{
    public function findUser(array $credentials): ? User
    {
        //add comment
        if (!isset($credentials['user_name'])) {
            return null;
        }
        $user = User::where('status',1)->where('user_name', $credentials['user_name'])->first();
        if (!$user) {
            return null;
        }
        return $user;
    }
}
