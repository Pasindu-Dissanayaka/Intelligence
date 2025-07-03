<?php

use App\Middleware\AuthMiddleware;

// Login Pages
app()->get('/register', 'AuthenticationsController@registerPage');
app()->get('/register/validate', 'AuthenticationsController@registerAction');
app()->get('/login', 'AuthenticationsController@loginPage');
app()->get('/login/validate', 'AuthenticationsController@loginAction');
app()->get('/refresh', 'AuthenticationsController@refreshToken');

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
