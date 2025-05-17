<?php

namespace App\Contracts;

interface VerificationStrategy
{
    public function sendCode($request);
    public function verifyCode($request);
}
