<?php

namespace App\Services\Sender;

use Illuminate\Support\Facades\Http;

class WhatsAppService
{
    public function send($message, $to)
    {
        $body = $message;
        $url = config('services.ultramsg.api_url');
        $params = ['token' => config('services.ultramsg.token'), 'to' => $to, 'body' => $body];

        try {
            $response = Http::asForm()->post($url, $params);
            if ($response->successful()) {
                return  'success';
            } else {
                return 'failed';
            }
        } catch (\Exception $e) {
            return 'error';
        }
    }
}
