<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    protected string $apiKey;
    protected string $baseUrl = 'https://openrouter.ai/api/v1';
    protected string $model = 'arcee-ai/trinity-large-preview:free';

    public function __construct()
    {
        $this->apiKey = config('services.openrouter.key') ?? '';
    }

    /**
     * Generate content using OpenRouter
     *
     * @param string $prompt
     * @return string|null
     */
    public function generateContent(string $prompt): ?string
    {
        if (empty($this->apiKey)) {
            throw new \Exception('OpenRouter API Key belum dikonfigurasi. Harap tambahkan OPENROUTER_API_KEY di file .env');
        }

        try {
            $response = Http::timeout(180)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                    'HTTP-Referer' => config('app.url'), // Optional, for rankings
                    'X-Title' => config('app.name'), // Optional, for rankings
                ])
                ->post($this->baseUrl . '/chat/completions', [
                    'model' => $this->model,
                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ],
                ]);

            if ($response->successful()) {
                return $response->json('choices.0.message.content');
            }

            $errorMessage = $response->json('error.message') ?? $response->body();
            Log::error('OpenRouter API Error', [
                'status' => $response->status(),
                'error' => $errorMessage
            ]);

            throw new \Exception('OpenRouter API Error: ' . $errorMessage);
        } catch (\Exception $e) {
            Log::error('AIService Exception', ['message' => $e->getMessage()]);
            throw $e;
        }
    }
}
