<?php

namespace App\Http\Controllers\Dashboard\Auth;

use App\Http\Controllers\Dashboard\BaseController;
use App\Http\Requests\Dashboard\Auth\ForgotPassword\ForgotPasswordRequest;
use App\Http\Requests\Dashboard\Auth\ForgotPassword\ResetPasswordRequest;
use App\Http\Requests\Dashboard\Auth\ForgotPassword\SendCodeRequest;
use App\Http\Requests\Dashboard\Auth\LoginRequest;
use App\Services\Dashboard\Auth\AuthenticationService;
use Illuminate\Http\Request;


class AuthenticationController extends BaseController
{
    protected  $authenticationService;
    public function __construct(AuthenticationService $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }

    public function login(LoginRequest $request)
    {
        $response = $this->authenticationService->login($request->all());
        return $response == 'error'
            ? $this->respondError(__('general.wrong_credentials'))
            : ($response == 'not_verified'
                ? $this->respondError(__('general.verfiy_account'))
                : $this->respondData($response, __('general.login_successfully')));
    }
    public function forgot(SendCodeRequest $request)
    {
        $translatedType = $request->method === 'email' ? __('general.email') : __('general.phone');
        $response = $this->authenticationService->forgot($request);
        return $response == 'user not found'
            ? $this->respondError(__('general.wrong_credentials'))
            : $this->respondData(
                $response,
                __('general.check_service', ['type' => $translatedType])
            );
    }

    public function reset(ResetPasswordRequest $request)
    {
        return $this->respondData($this->authenticationService->reset($request));
    }

    public function resetPassword(ForgotPasswordRequest $request)
    {
        return $this->authenticationService->resetPassword($request);
    }

    public function logout(Request $request)
    {
        return $this->authenticationService->logout($request)
            == 'success'
            ? $this->respondMessage(__('general.you_are_logout'))
            : false;
    }


    // public function changeEmail(Request $request)
    // {
    //     $response =  $this->authenticationService->changeEmail($request);
    //     return $response == 'send_email'
    //         ? $this->respondMessage(__('general.sent_email'))
    //         : ($response == 'send_phone'
    //             ? $this->respondMessage(__('general.sent_phone'))
    //             : $this->respondError(__('general.error')));
    //     $this->respondMessage(__('general.error'));
    // }
    // public function verifyEmailChange(Request $request)
    // {
    //     $response = $this->authenticationService->verifyEmailChange($request);
    //     return $response == 'email_changed'
    //         ? $this->respondMessage(__('general.email_changed'))
    //         : ($response == 'phone_changed'
    //             ? $this->respondMessage(__('general.phone_changed'))
    //             : $this->respondError(__('general.invalid_or_expired_code')));
    // }
    // public function updateProfile(Request $request)
    // {
    //     $response = $this->authenticationService->updateProfile($request);
    //     return $response == 'error'
    //         ? $this->respondError(__('general.something_wrong'))
    //         : ($response == 'not_found'
    //             ? $this->respondMessage(__('general.not_found'), 404)
    //             : $this->respondData($response, __('general.updated')));
    // }

    // public function updatePassword(Request $request)
    // {
    //     $response = $this->authenticationService->updatePassword($request);
    //     return $response  == 'success'
    //         ? $this->respondMessage(__('general.updated'))
    //         : $this->respondMessage(__('general.wrong_credentials'), 404);
    // }
}
