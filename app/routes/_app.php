<?php

use App\Middleware\AuthMiddleware;

// Default path to Login
app()->response()->redirect('/login');

// Login Pages
app()->get('/register', ['middleware' => 'auth', 'AuthenticationsController@registerPage']);
app()->get('/register/validate', ['middleware' => 'auth', 'AuthenticationsController@registerAction']);
app()->get('/login', ['middleware' => 'auth', 'AuthenticationsController@loginPage']);
app()->get('/login/validate', ['middleware' => 'auth', 'AuthenticationsController@loginAction']);
app()->get('/refresh', ['middleware' => 'auth', 'AuthenticationsController@refreshToken']);

app()->group('/dashboard', ['middleware' => AuthMiddleware::class, function () {
  app()->get('/me', 'AuthenticationsController@me');

  app()->get('/', function () {
    echo 'User Greeting Page';
  });

  app()->get('/ask-ai', function () {
    echo 'Talk to our AI but with your daily limit';
  });

  app()->get('/history', function () {
    echo 'See your previous prompts & AI responses';
  });

  app()->get('/analytics', function () {
    echo 'Check your  basic usage stats';
  });
}]);
