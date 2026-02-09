<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TripayService
{
    protected $apiKey;
    protected $privateKey;
    protected $merchantCode;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.tripay.api_key');
        $this->privateKey = config('services.tripay.private_key');
        $this->merchantCode = config('services.tripay.merchant_code');
        $this->baseUrl = config('services.tripay.mode') === 'production' 
            ? 'https://tripay.co.id/api/' 
            : 'https://tripay.co.id/api-sandbox/';
    }

    public function getPaymentChannels()
    {
        try {
            $response = Http::withToken($this->apiKey)
                ->get($this->baseUrl . 'merchant/payment-channels');

            if ($response->successful()) {
                return $response->json()['data'];
            }

            Log::error('Tripay getPaymentChannels error: ' . $response->body());
            return [];
        } catch (\Exception $e) {
            Log::error('Tripay getPaymentChannels exception: ' . $e->getMessage());
            return [];
        }
    }

    public function createTransaction($data)
    {
        $signature = hash_hmac('sha256', $this->merchantCode . $data['merchant_ref'] . $data['amount'], $this->privateKey);

        $payload = [
            'method'         => $data['method'],
            'merchant_ref'   => $data['merchant_ref'],
            'amount'         => $data['amount'],
            'customer_name'  => $data['customer_name'],
            'customer_email' => $data['customer_email'],
            'customer_phone' => $data['customer_phone'] ?? '',
            'order_items'    => $data['order_items'],
            'callback_url'   => route('payment.callback'),
            'return_url'     => route('payment.success'),
            'expired_time'   => (time() + (24 * 60 * 60)), // 24 hours
            'signature'      => $signature
        ];

        try {
            $response = Http::withToken($this->apiKey)
                ->post($this->baseUrl . 'transaction/create', $payload);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Tripay createTransaction error: ' . $response->body());
            return $response->json();
        } catch (\Exception $e) {
            Log::error('Tripay createTransaction exception: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
