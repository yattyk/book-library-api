<?php
namespace App\Controllers;

use App\Support\Response;
use App\Services\AuthService;

final class AuthController
{
    public function register(): string
    {
        $d = json_decode(file_get_contents('php://input'), true) ?? [];
        $login = trim((string) ($d['login'] ?? ''));
        $pass  = (string) ($d['password'] ?? '');
        $conf  = (string) ($d['confirm_password'] ?? '');
        if ($pass !== $conf || $login === '') {
            return Response::json(['error' => 'Validation failed'], 422);
        }
        try {
            $res = AuthService::register($login, $pass);
            return Response::json($res, 201);
        } catch (\Throwable $e) {
            return Response::json(['error' => $e->getMessage()], 400);
        }
    }

    public function login(): string
    {
        $d = json_decode(file_get_contents('php://input'), true) ?? [];
        try {
            $res = AuthService::login((string) $d['login'], (string) $d['password']);
            return Response::json($res);
        } catch (\Throwable $e) {
            return Response::json(['error' => $e->getMessage()], 401);
        }
    }
}
