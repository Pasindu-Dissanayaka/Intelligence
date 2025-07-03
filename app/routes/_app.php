<?php

use App\Middleware\AuthMiddleware;

// Login Pages
app()->get('/register', 'AuthenticationsController@registerPage');
app()->post('/register/validate', 'AuthenticationsController@registerAction');
app()->get('/login', 'AuthenticationsController@loginPage');
app()->post('/login/validate', 'AuthenticationsController@loginAction');
app()->post('/refresh', 'AuthenticationsController@refreshToken');

app()->post('/ask-ai', ['middleware' => AuthMiddleware::class, 'ChatsController@ask']);
app()->post('/history', ['middleware' => AuthMiddleware::class, 'ChatsController@history']);
app()->post('/analytics', ['middleware' => AuthMiddleware::class, 'ChatsController@analytics']);

app()->group('/dashboard', ['middleware' => AuthMiddleware::class, function () {
  app()->get('/me', 'AuthenticationsController@me');

  app()->get('/', function () {
    response()->view('app.interface');
  });

  app()->get('/history', 'ChatsController@historyPage');

  app()->get('/analytics', function () {
    response()->view('app.analytics');
  });
}]);
