<?php

namespace App\Services\Dashboard\Auth\ResetPassword;

use App\Models\ResetCodePassword;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class SendWhatsAppCode
{
    public function sendcode($request)
    {
        $verfication_code = generateCode();
        $body = __('general.your_verfication_code').':' . $verfication_code;
        $url = config('services.ultramsg.api_url');
        $params = ['token' =>  config('services.ultramsg.token'), 'to' => $request->phone, 'body' => $body];
        try {
            $response = Http::asForm()->post($url, $params);

            if ($response->successful()) {
                ResetCodePassword::updateOrCreate(
                    ['phone' =>  $request->phone],
                    [
                        'verification_code' => $verfication_code,
                        'expires_at' => Carbon::now()->addMinutes(10),
                        'attempts' => 0,
                        'account_locked' => false,
                        'account_locked_until' => null,
                    ]
                );
                return  'success';
            } else {
                return 'failed';
            }
        } catch (\Exception $e) {
            return 'error';
        }
    }
    public function verifyCode($request)
    {
        try {
            $verificationData = ResetCodePassword::where('phone', $request->phone)->first();

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
                    'message' => __('general.account_is_temporarily'). $verificationData->account_locked_until->diffForHumans()
                ];
            }

            if ($verificationData->verification_code !== $request->code) {
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
                    'message' =>  __('general.invalid_code') . $verificationData->attempts . ' of 3.'
                ];
            }

            session(['verification_id' => $verificationData->id]);

            return [
                'status' => 'success',
                'message' => __('general.code_successfully'),
                'verification_id' => $verificationData->id
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
                    'message' =>  __('general.invalid_code')
                ];
            }

            $user = User::wherePhone($verificationData->phone)->first();

            if (!$user) {
                return [
                    'status' => 'error',
                    'message' =>__('general.user_not_found')
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
