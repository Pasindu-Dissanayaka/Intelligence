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
        $msg = new Message();
        $msg->userID = $userID;
        $msg->message = $message;
        $msg->is_bot = 0;
        $msg->sent_at = $message_time;
        $msg->usage = null;
        $msg->save();

        $client = new Client();
        try {
            $response = $client->request('POST', 'https://api.openai.com/v1/chat/completions', [
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
            // TODO: We'll send to Error Log
        }
        $reply = $data['choices'][0]['message']['content'] ?? "Sorry the OpenAI endpoint is busy at the moment. Please try again later.";
        $prompt_tokens = $data['usage']['prompt_tokens'] ?? 0;
        $completion_tokens = $data['usage']['completion_tokens'] ?? 0;
        $total_tokens = $data['usage']['total_tokens'] ?? 0;

        // Mark reply time
        $reply_time = tick()->now();

        // Usage JSON Object
        $usageData = [ 'prompt_tokens' => $prompt_tokens, 'completion_tokens' => $completion_tokens, 'total_tokens' => $total_tokens, ];

        // Save bot response
        $res = new Message();
        $res->userID = $userID;
        $res->message = $reply;
        $res->is_bot = 1;
        $res->sent_at = $reply_time;
        $res->usage = json_encode($usageData);
        $res->save();
        response()->json(['input' => $message, 'message_time' => $message_time, 'reply' => $reply, 'reply_time' => $reply_time]);
    }

    public function get_response()
    {
        $messages = Message::where('userID', $this->currentUserID)
            ->orderBy('sent_at', 'asc')
            ->get()
            ->map(function ($msg) {
                return [
                    'from' => $msg->is_bot ? 'Agent' : 'You',
                    'message' => $msg->message,
                    'timestamp' => $msg->sent_at
                ];
            });

        response()->json(['messages' => $messages]);
    }

    public function historyPage()
    {
        $username = User::find($this->currentUserID)['name'];
        $messages = Message::where('userID', $this->currentUserID)->orderBy('sent_at', 'asc')->get();
        response()->view('app.history', ['conversations' => $messages, 'username' => $username]);
    }

    public function interfacePage(){
        $username = User::find($this->currentUserID)['name'];
        response()->view('app.interface', ['username' => $username]);
    }

    public function analytics()
    {
        $user = User::find($this->currentUserID);
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $messages = $user->messages;
        $totalMessages = $messages->count();
        $userMessages = $messages->where('is_bot', 0)->count();
        $botMessages = $messages->where('is_bot', 1)->count();

        // Total token usage from OpenAI API
        $totalTokens = $messages->sum(function ($msg) {
            if (!$msg->token_usage) return 0; // skip nulls
            $usage = json_decode($msg->token_usage, true);
            return $usage['total_tokens'] ?? 0;
        });

        // Daily usage breakdown
        $daily = $messages->groupBy(function ($msg) {
            return \Carbon\Carbon::parse($msg->sent_at)->format('Y-m-d');
        })->map(function ($dayMsgs) {
            return [
                'user' => $dayMsgs->where('is_bot', 0)->count(),
                'bot' => $dayMsgs->where('is_bot', 1)->count(),
            ];
        });

        return response()->json([
            'stats' => [
                'total' => $totalMessages,
                'user_sent' => $userMessages,
                'bot_replies' => $botMessages,
                'token_estimate' => $totalTokens
            ],
            'daily' => $daily,
            'username' => $user['name']
        ]);
    }

    public function analyticsPage() {
        $user = User::find($this->currentUserID);
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $username = $user->name;
        $messages = $user->messages;
        $totalMessages = $messages->count();
        $userMessages = $messages->where('is_bot', 0)->count();
        $botMessages = $messages->where('is_bot', 1)->count();

        // Total token usage from OpenAI API
        $totalTokens = $messages->sum(function ($msg) {
            if (!$msg->token_usage) return 0; // skip nulls
            $usage = json_decode($msg->token_usage, true);
            return $usage['total_tokens'] ?? 0;
        });

        // Daily usage breakdown
        $daily = $messages->groupBy(function ($msg) {
            return \Carbon\Carbon::parse($msg->sent_at)->format('Y-m-d');
        })->map(function ($dayMsgs) {
            return [
                'user' => $dayMsgs->where('is_bot', 0)->count(),
                'bot' => $dayMsgs->where('is_bot', 1)->count(),
            ];
        });
        
        return response()->view('app.analytics', ['stats' => [ 'total' => $totalMessages, 'user_sent' => $userMessages, 'bot_replies' => $botMessages, 'token_estimate' => $totalTokens ], 'daily' => $daily, 'username' => $username]);
    }
}
