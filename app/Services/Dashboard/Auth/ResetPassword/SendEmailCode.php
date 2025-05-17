<?php

namespace App\Services\Dashboard\Auth\ResetPassword;

use App\Mail\SendResetCodeMail;
use App\Models\ResetCodePassword;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class SendEmailCode
{
    public function sendCode($request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $code = generateCode();

        ResetCodePassword::updateOrCreate(
            ['email' =>  $request->email],
            [
                'verification_code' => $code,
                'expires_at' => Carbon::now()->addMinutes(10),
                'attempts' => 0,
                'account_locked' => false,
                'account_locked_until' => null,
            ]
        );
        Mail::to($request->email)->send(new SendResetCodeMail($code));

        return  'success';
    }

    public function verifyCode($request)
    {
        try {
            $verificationData = ResetCodePassword::where('email', $request->email)->first();

            if (!$verificationData) {
                return [
                    'status' => 'error',
                    'message' => __('general.code_not_found')

                ];
            }

            if (
                $verificationData->account_locked
                && $verificationData->account_locked_until
                && now()->lessThan($verificationData->account_locked_until)
            ) {
                return [
                    'status' => 'error',
                    'message' => __('general.account_is_temporarily') . $verificationData->account_locked_until->diffForHumans()
                ];
            }

            if ($verificationData->verification_code === $request->code) {
                session(['verification_id' => $verificationData->id]);

                return [
                    'status' => 'success',
                    'message' => __('general.code_successfully'),
                    'verification_id' => $verificationData->id
                ];
            }

            $verificationData->increment('attempts');

            if ($verificationData->attempts >= 3) {
                $verificationData->update([
                    'account_locked' => true,
                    'account_locked_until' => now()->addMinutes(5),
                ]);

                return [
                    'status' => 'error',
                    'message' => __('general.too_many_failed')
                ];
            }

            return [
                'status' => 'error',
                'message' => __('general.invalid_code')  . $verificationData->attempts . ' of 3.'
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'An error occurred: ' . $e->getMessage()
            ];
        }
    }

    public function resetPassword($request)
    {
        try {

            $verification_id = session('verification_id') ?? $request->verification_id;
            $verificationData = ResetCodePassword::find($verification_id);

            if (!$verificationData) {
                return [
                    'status' => 'error',
                    'message' => __('general.invalid_code')
                ];
            }

            $user = User::whereEmail($verificationData->email)->first();

            if (!$user) {
                return [
                    'status' => 'error',
                    'message' => __('general.user_not_found')
                ];
            }

            $user->update([
                'password' => $request->password
            ]);

            $verificationData->delete();

            return [
                'status' => 'success',
                'message' => __('general.reset_successfully')
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'An error occurred: ' . $e->getMessage()
            ];
        }
    }
}
