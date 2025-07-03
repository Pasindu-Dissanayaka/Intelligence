<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Message;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;


class ChatsController extends Controller
{
    protected $currentUserID;

    public function __construct()
    {

        $request = request()->next();
        $this->currentUserID = $request['id'];
    }

    public function ask()
    {
        $userID = $this->currentUserID;
        $validatedData = request()->validate(['message' => 'string']);

        if (!$validatedData || !$userID) {
            return response()->json(['error' => 'Invalid request'], 400);
        }
        
        $message = $validatedData['message'];
        $message_time = tick()->now();

        // Save user message
        Message::create([
            'userID' => $userID,
            'message' => $message,
            'is_bot' => 0,
            'sent_at' => $message_time
        ]);

        $client = new Client();
        try {
        $response = $client->request('POST','https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . _env('OPENAI_API_KEY', null),
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'model' => 'gpt-4.1',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a helpful assistant.'],
                    ['role' => 'user', 'content' => $message]
                ]
            ]
        ]);

        $data = json_decode($response->getBody(), true);
        
    } catch (\Exception $e) {
        // return response()->json(['error' => 'OpenAI error: ' . $e->getMessage()], 500);
    }
        $reply = $data['choices'][0]['message']['content'] ?? "Sorry the OpenAI endpoint is busy at the moment. Please try again later.";
        // Mark reply time
        $reply_time = tick()->now();
        // Save bot response
        Message::create([
            'userID' => $userID,
            'message' => $reply,
            'is_bot' => 1,
            'sent_at' => $reply_time
        ]);
        response()->json(['input'=>$message, 'message_time'=>$message_time, 'reply' => $reply, 'reply_time'=> $reply_time]);
    }

    public function get_response()
    {
        $messages = Message::where('user_id', $this->currentUserID)
            ->orderBy('sent_at', 'asc')
            ->get()
            ->map(function ($msg) {
                return [
                    'from' => $msg->is_bot ? 'Agent' : 'You',
                    'message' => $msg->decrypted_message,
                    'timestamp' => $msg->sent_at
                ];
            });

        response()->json(['messages' => $messages]);
    }
}
