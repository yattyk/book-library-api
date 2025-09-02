<?php
use App\Controllers\AuthController;
use App\Controllers\UsersController;
use App\Middleware\AuthMiddleware;

$auth = [AuthMiddleware::class . '::ensure'];

$router->add('POST', '/api/register', [AuthController::class, 'register']);
$router->add('POST', '/api/login',    [AuthController::class, 'login']);

$router->add('GET',  '/api/users', [UsersController::class, 'index'], $auth);
