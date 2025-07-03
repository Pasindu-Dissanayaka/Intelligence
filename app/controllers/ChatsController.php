<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Message;
use GuzzleHttp\Client;

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

        // Save user message
        Message::create([
            'userID' => $userID,
            'message' => $message,
            'is_bot' => 0,
            'sent_at' => tick()->now()
        ]);

        $client = new Client();
        $response = $client->post('https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . _env('OPENAI_API_KEY', null),
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a helpful assistant.'],
                    ['role' => 'user', 'content' => $message]
                ]
            ]
        ]);

        $data = json_decode($response->getBody(), true);
        $reply = $data['choices'][0]['message']['content'] ?? "Something went wrong.";

        // Save bot response
        Message::create([
            'userID' => $userID,
            'message' => $reply,
            'is_bot' => 1,
            'sent_at' => tick()->now()
        ]);

        response()->json(['reply' => $reply]);
    }

    public function get_response()
    {
        $messages = Message::where('user_id', $this->currentUserID)
            ->orderBy('sent_at', 'asc')
            ->get()
            ->map(function ($msg) {
                return [
                    'from' => $msg->is_bot ? 'GPT' : 'You',
                    'message' => $msg->decrypted_message,
                    'timestamp' => $msg->sent_at
                ];
            });

        response()->json(['messages' => $messages]);
    }
}
