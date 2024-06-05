<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;
use App\Jobs\TranslateAndBroadcastMessage;

class ChatsController extends Controller
{
    //Add the below functions
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('chat');
    }

    public function fetchMessages()
    {
        return Message::with('user')->get();
    }

    public function sendMessage(Request $request)
    {
        $user = Auth::user();
        $messageText = $request->input('message');
        // $message = $user->messages()->create([
        //     'message' => $request->input('message')
        // ]);
        
        // broadcast(new MessageSent($user, $message))->toOthers();
    
        TranslateAndBroadcastMessage::dispatch($user->id, $messageText);
        
        return ['status' => 'Message Sent!'];
    }
}

