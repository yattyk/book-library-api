<?php
use App\Controllers\AuthController;
use App\Controllers\UsersController;
use App\Middleware\AuthMiddleware;
use App\Controllers\BooksController;

$auth = [AuthMiddleware::class . '::ensure'];

$router->add('POST', '/api/register', [AuthController::class, 'register']);
$router->add('POST', '/api/login',    [AuthController::class, 'login']);
$router->add('POST', '/api/permissions/grant', [UsersController::class, 'grantAccess'], $auth);

$router->add('GET',  '/api/users', [UsersController::class, 'index'], $auth);
$router->add('GET', '/api/books', [BooksController::class, 'myBooks'], $auth);
$router->add('GET', '/api/books/{id}', [BooksController::class, 'show'], $auth);
