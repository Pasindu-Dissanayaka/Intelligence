<?php

use App\Middleware\AuthMiddleware;

// Login Pages
app()->get('/register', 'AuthenticationsController@registerPage');
app()->post('/register/validate', 'AuthenticationsController@registerAction');
app()->get('/login', 'AuthenticationsController@loginPage');
app()->post('/login/validate', 'AuthenticationsController@loginAction');
app()->post('/refresh', 'AuthenticationsController@refreshToken');

app()->group('/dashboard', ['middleware' => AuthMiddleware::class, function () {
  app()->get('/me', 'AuthenticationsController@me');

  app()->get('/', function () {
    response()->view('app.interface');
  });

  app()->post('/ask-ai', 'ChatsController@ask');

  app()->get('/history', function () {
    echo 'See your previous prompts & AI responses';
  });

  app()->get('/analytics', function () {
    echo 'Check your  basic usage stats';
  });
}]);
