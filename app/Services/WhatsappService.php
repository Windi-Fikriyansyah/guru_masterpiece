<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappService
{
    protected $token;

    public function __construct()
    {
        $this->token = config('services.fonnte.token');
    }

    /**
     * Send message via Fonnte
     */
    public function sendMessage($target, $message)
    {
        if (empty($this->token)) {
            Log::warning('WhatsApp service: FONNTE_TOKEN is not set.');
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->post('https://api.fonnte.com/send', [
                'target' => $target,
                'message' => $message,
                'countryCode' => '62', // Default Indonesia
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Fonnte send error: ' . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::error('Fonnte exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Normalize phone number to 62xx format
     */
    public static function normalizeNumber($number)
    {
        $number = preg_replace('/[^0-9]/', '', $number);

        if (str_starts_with($number, '0')) {
            $number = '62' . substr($number, 1);
        } elseif (str_starts_with($number, '8')) {
            $number = '62' . $number;
        }

        return $number;
    }
}
