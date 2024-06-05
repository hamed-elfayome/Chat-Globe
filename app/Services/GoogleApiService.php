<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class GoogleApiService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://generativelanguage.googleapis.com/v1beta/',
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    public function translateText($apiKey, $text, $lang = 'english')
    {
        $endpoint = 'models/gemini-1.5-flash:generateContent?key=' . $apiKey;

        $body = [
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => 'translate the next text into ' . $lang . ' reply with the translation only' . ', the text:" '.$text . ' "',
                        ],
                    ],
                ],
            ],
        ];

        try {
            $response = $this->client->post($endpoint, ['json' => $body]);
            $data = json_decode($response->getBody(), true);
            // Assuming the response structure contains the translated text
            $translatedText = $data['candidates'][0]['content']['parts'][0]['text']?? null;
            return $translatedText;
        } catch (GuzzleException $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
