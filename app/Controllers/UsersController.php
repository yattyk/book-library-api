<?php
namespace App\Controllers;

use App\Support\Response;
use App\Models\User;

final class UsersController
{
    public function index(): string
    {
        return Response::json(User::all());
    }

    public function grantAccess(): string
    {
        $uid = (int)($GLOBALS['auth_user_id'] ?? 0);
        $d = json_decode(file_get_contents('php://input'), true) ?? [];
        $grantee = (int)($d['user_id'] ?? 0);
        if ($grantee <= 0 || $grantee === $uid) {
            return Response::json(['error' => 'Invalid user'], 422);
        }
        \App\Models\Permission::grant($uid, $grantee);
        return Response::json(['status' => 'ok']);
    }

}
