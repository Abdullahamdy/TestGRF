<?php

namespace App\Contracts;

use App\Models\User;

interface LoginStrategyInterface
{
    public function findUser(array $credentials): ?User;
}
