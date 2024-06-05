<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GoogleApiService;

class TestController extends Controller
{
    protected $googleApiService;

    public function __construct(GoogleApiService $googleApiService)
    {
        $this->googleApiService = $googleApiService;
    }

    public function testTranslation(Request $request)
    {
        $text = $request->input('text', 'Write a story about a magic backpack.');
        $apiKey = config('services.google.api_key');
        $translatedText = $this->googleApiService->translateText($apiKey, $text);

        if (isset($translatedText['error'])) {
            return response()->json(['status' => 'Translation failed!', 'error' => $translatedText['error']], 500);
        }

        return response()->json(['status' => 'Translation successful!', 'translatedText' => $translatedText]);
    }
}
