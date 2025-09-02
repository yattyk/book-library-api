<?php
use App\Controllers\AuthController;

$router->add('POST', '/api/register', [AuthController::class, 'register']);
$router->add('POST', '/api/login',    [AuthController::class, 'login']);
