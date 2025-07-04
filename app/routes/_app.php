<?php

use App\Middleware\AuthMiddleware;

// Redirect All Visitors to Login Page
app()->get('/', function () {
  response()->redirect('/login');
});

// Login Pages
app()->get('/register', function () {
  response()->render('auth.register');
});
app()->post('/register/validate', 'AuthenticationsController@registerAction');

app()->get('/login', function () {
  response()->render('auth.login');
});

app()->post('/login/validate', 'AuthenticationsController@loginAction');
app()->post('/refresh', 'AuthenticationsController@refreshToken');
app()->get('/logout', 'AuthenticationsController@logoutPage');
app()->post('/logout/validate', 'AuthenticationsController@logoutAction');

app()->post('/ask-ai', ['middleware' => AuthMiddleware::class, 'ChatsController@ask']);
app()->post('/history', ['middleware' => AuthMiddleware::class, 'ChatsController@history']);
app()->post('/analytics', ['middleware' => AuthMiddleware::class, 'ChatsController@analytics']);

app()->group('/dashboard', ['middleware' => AuthMiddleware::class, function () {
  app()->get('/me', 'AuthenticationsController@me');

  app()->get('/', 'ChatsController@interfacePage');

  app()->get('/history', 'ChatsController@historyPage');

  app()->get('/analytics', 'ChatsController@analyticsPage');
}]);