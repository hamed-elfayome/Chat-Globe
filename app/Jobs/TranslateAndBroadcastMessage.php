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

class TranslateAndBroadcastMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $messageText;
    protected $userId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userId, $messageText)
    {
        $this->userId = $userId;
        $this->messageText = $messageText;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(GoogleApiService $googleApiService)
    {
        $apiKey = config('services.google.api_key');
        $translatedMessage = $googleApiService->translateText($apiKey, $this->messageText);

        if ($translatedMessage) {
            $user = User::find($this->userId);
            $message = $user->messages()->create([
                'message' => $translatedMessage
            ]);

            broadcast(new MessageSent($user, $message))->toOthers();
        }
    }
}
