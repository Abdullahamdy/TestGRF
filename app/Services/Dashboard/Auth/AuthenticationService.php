<?php

namespace App\Services\Dashboard\Auth;

use App\Http\Resources\Dashboard\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthenticationService
{
    public function __construct()
    {
        // Initialization
    }

    public function login(array $credentials)
    {
        $strategy =   $this->getStrategyClass($credentials);
        $user = $strategy->findUser($credentials);

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return 'error';
        }
        $user->token = $user->createToken('admin', ['admin'])->plainTextToken;
        return new UserResource($user);
    }

    public function getStrategyClass($credentials)
    {
        $key = isset($credentials['phone']) ? 'phone' : 'user_name';
        if ($key == 'user_name') {
            $strategyClass = "App\\Contracts\\UsernameLoginStrategy";
        } else {

            $strategyClass = "App\\Contracts\\" . Str::studly($key) . "LoginStrategy";
        }
        return  new $strategyClass();
    }
    public function forgot($request)
    {
        $service = $this->getStrategyForResetPasswordClass($request);
        return   $service->sendcode($request);
    }

    public function reset($request)
    {
        $service = $this->getStrategyForResetPasswordClass($request);
        return   $service->verifyCode($request);
    }

    public function resetPassword($request)
    {
        $service = $this->getStrategyForResetPasswordClass($request);
        return   $service->resetPassword($request);
        // return (new UserResource($user));
    }

    public function logout($request)
    {
        $request->user()->currentAccessToken()->delete();
        return 'success';
    }
    public function getStrategyForResetPasswordClass($credentials)
    {
        $key = $credentials->method == 'phone' ? 'whatsApp' : 'email';
        $service = "App\\Services\\Dashboard\\Auth\\ResetPassword\\" . 'Send' . Str::studly($key) . "Code";
        return  new $service();
    }
}
