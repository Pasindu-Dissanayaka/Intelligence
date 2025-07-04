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
    private $domain;

    public function __construct()
    {
        $this->privateKey = file_get_contents(StoragePath('/keys/private.key'));
        $this->publicKey = file_get_contents(StoragePath('/keys/public.key'));
        $this->domain = _env('APP_URL', '');
    }
    
    public function call()
    {
        // Read token value from cookie
        $token = request()->cookies('secureToken');
        // validate the token value (is required)
        if (!$token) {
            response()->json(['error' => 'Unauthorized'], 401);
            exit;
        }

        try {
            $decoded = JWT::decode($token, new Key($this->publicKey, $this->algorithm));
            $user = User::where('id', $decoded->sub)->first();
            if (!$user) throw new \Exception('User not found');
            response()->next($user);
        } catch (\Exception $e) {
            response()->json(['error' => 'Invalid token'], 401);
            exit;
        }
    }
}