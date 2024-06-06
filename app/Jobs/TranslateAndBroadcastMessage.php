<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Message;
use App\Models\User;
use App\Services\GoogleApiService;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Log;


class TranslateAndBroadcastMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $messageText;
    protected $userId;
    protected $language;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userId, $messageText, $language)
    {
        $this->userId = $userId;
        $this->messageText = $messageText;
        $this->language = $language;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(GoogleApiService $googleApiService)
    {
        $apiKey = config('services.google.api_key');

        $user = User::find($this->userId);

        foreach ($this->language as $lang) {
            $message = new Message();
            $message->user_id = $user->id;
            $message->language = $lang;

            if ($lang != $user->language) {
                $translatedMessage = $googleApiService->translateText($apiKey, $this->messageText, $lang);
                $message->message = $translatedMessage;
            } else {
                $message->message = $this->messageText;
            }

            $message->save();
        }

        broadcast(new MessageSent($user));


    }
}
