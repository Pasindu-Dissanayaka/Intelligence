<?php

namespace App\Middleware;

use Leaf\Middleware;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthMiddleware extends Middleware
{
    private $privateKey;
    private $publicKey;
    private $algorithm = "RS256";  // Using RS256 to use Keys for signing rather than a password.
    private $expire = 3600; // 1 hour

    public function __construct()
    {
        $this->privateKey = file_get_contents(StoragePath('/keys/private.key'));
        $this->publicKey = file_get_contents(StoragePath('/keys/public.key'));
    }
    
    public function call()
    {
        $headers = apache_request_headers();
        $auth = $headers['Authorization'] ?? '';

        if (!preg_match('/Bearer\s(\S+)/', $auth, $matches)) {
            response()->json(['error' => 'Unauthorized'], 401);
            exit;
        }

        try {
            $decoded = JWT::decode($matches[1], new Key($this->publicKey, $this->algorithm));
            $user = User::where('id', $decoded->sub)->first();
            if (!$user) throw new \Exception('User not found');

            // Store in global if needed
            app()->set('auth_user', $user);
        } catch (\Exception $e) {
            response()->json(['error' => 'Invalid token'], 401);
            exit;
        }
    }
}
