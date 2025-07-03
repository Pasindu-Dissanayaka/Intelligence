<?php

namespace App\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Leaf\Http\Response;
use Leaf\Db;

class authsController extends Controller
{
    private $privateKey;
    private $publicKey;
    private $algorithm = "RS256";  // Using RS256 to use Keys for signing rather than a password.
    private $expire = 3600; // 1 hour

    public function __construct(){
        $this->privateKey = file_get_contents(StoragePath('/keys/private.key'));
        $this->publicKey = file_get_contents(StoragePath('/keys/public.key'));
    }

    public function loginPage(){
        response()->render('auth.login');
    }

    public function loginAction(){
        
    }

    public function registerPage(){
        response()->render('auth.register');
    }

    public function registerAction(){
        response()->render('auth');
    }
}
