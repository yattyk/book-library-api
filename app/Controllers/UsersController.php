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
}
