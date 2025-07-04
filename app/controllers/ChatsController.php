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
        }
        $reply = $data['choices'][0]['message']['content'] ?? "Sorry the OpenAI endpoint is busy at the moment. Please try again later.";
        $prompt_tokens = $data['usage']['prompt_tokens'] ?? 0;
        $completion_tokens = $data['usage']['completion_tokens'] ?? 0;
        $total_tokens = $data['usage']['total_tokens'] ?? 0;
        // Mark reply time
        $reply_time = tick()->now();
        // Save bot response
        Message::create([
            'userID' => $userID,
            'message' => $reply,
            'is_bot' => 1,
            'sent_at' => $reply_time,
            'usage' => "{ 'prompt_tokens': $prompt_tokens, 'completion_tokens': $completion_tokens, 'total_tokens': $total_tokens }"
        ]);
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
                    'message' => $msg->decrypted_message,
                    'timestamp' => $msg->sent_at
                ];
            });

        response()->json(['messages' => $messages]);
    }

    public function historyPage()
    {
        $messages = Message::where('userID', $this->currentUserID)->orderBy('sent_at', 'asc')->get();
        response()->view('app.history', ['conversations' => $messages]);
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
        $firstMessage = $messages->sortBy('created_at')->first()?->created_at;

        // Optional: Estimated token usage (rough estimate: 1 token ~= 4 chars)
        $totalTokenEstimate = $messages->sum(function ($msg) {
            return ceil(strlen($msg->decrypted_message) / 4);
        });

        // Optional: Daily usage breakdown
        $daily = $messages->groupBy(function ($msg) {
            return \Carbon\Carbon::parse($msg->created_at)->format('Y-m-d');
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
                'first_message' => $firstMessage,
                'token_estimate' => $totalTokenEstimate
            ],
            'daily' => $daily
        ]);
    }

    public function analyticsPage() {

    }
}
