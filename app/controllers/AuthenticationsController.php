<?php

namespace App\Controllers;

use App\Models\User;
use Leaf\Http\Cookie;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthenticationsController extends Controller
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

    public function loginPage()
    {
        response()->render('auth.login');
    }

    public function loginAction()
    {
        // Validate and Sanitize Inputs
        if (!$data = request()->validate(['email' => 'email|min:3', 'password' => 'alphaDash|min:12'])) {
            // validation failed, redirect back with errors //
            return response()->json(['error' => request()->errors()], 401);
        }
        // Load User Data if User Exists
        $user = User::where('email', $data['email'])->first();

        // Verify the Password
        if (!$user || !password_verify($data['password'], $user['password'])) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        // Cool! Let's do the JWT token now
        $payload = [
            'iss' => $this->domain,  // issuer I used an active domain for this
            'sub' => $user['id'],
            'iat' => time(),
            'exp' => time() + $this->expire
        ];

        $jwt = JWT::encode($payload, $this->privateKey, $this->algorithm);

        // Set token in secure httpOnly cookie
        cookie()->set('secureToken', $jwt, [
            'expire' => time() + $this->expire,
            'path' => '/',
            'domain' => '',
            'secure' => false,   // TODO: Change this to true
            'httponly' => true,
            'samesite' => 'strict'
        ]);
        // return success
        response()->json(['success' => true]);
    }

    public function registerPage()
    {
        response()->render('auth.register');
    }

    public function registerAction()
    {
        // Validate and Sanitize Inputs
        if (!$data = request()->validate(['email' => 'email', 'username' => 'text|min:3', 'password' => 'alphaDash|min:12'])) {
            // validation failed, redirect back with errors //
            return response()
                ->withFlash('errors', request()->errors())
                ->redirect('/register');
        }

        // Load User Data if User Exists
        $exists = User::where('email', $data['email'])->first();
        if ($exists) return response()->json(['error' => 'Email already registered'], 409);

        // Hash the password
        $hashed = password_hash($data['password'], PASSWORD_BCRYPT);

        // Create the user
        $user = User::create(['email' => $data['email'], 'password' => $hashed]);

        // Return success with UserID
        response()->json(['success' => true, 'user_id' => $user->id]);
    }

    public function refreshToken()
    {
        $token = request()->get('token');

        try {
            $decoded = JWT::decode($token, new Key($this->publicKey, $this->algorithm));
            $newPayload = [
                'iss' => $this->domain,  // issuer I used an active domain for this
                'sub' => $decoded->sub,
                'iat' => time(),
                'exp' => time() + $this->expire
            ];
            $newToken = JWT::encode($newPayload, $this->privateKey, $this->algorithm);
            response()->json(['token' => $newToken]);
        } catch (\Exception $e) {
            response()->json(['error' => 'Invalid token'], 401);
        }
    }

    public function me()
    {
        $user = $this->getUserFromToken();
        if (!$user) return response()->json(['error' => 'Unauthorized'], 401);
        response()->json($user);
    }

    private function getUserFromToken()
    {
        $headers = apache_request_headers();
        $auth = $headers['Authorization'] ?? '';

        if (!preg_match('/Bearer\s(\S+)/', $auth, $matches)) return null;

        try {
            $decoded = JWT::decode($matches[1], new Key($this->publicKey, $this->algorithm));
            return User::where('id', $decoded->sub)->first();
        } catch (\Exception $e) {
            return null;
        }
    }
}
