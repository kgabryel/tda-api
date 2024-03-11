<?php

namespace App\Shared\Infrastructure\Utils;

use App\User\Infrastructure\Model\User;
use Illuminate\Support\Facades\Auth;

abstract class UserUtils
{
    public static function isLogged(): bool
    {
        return Auth::user() !== null;
    }

    public static function getLoggedUser(): User
    {
        return Auth::user();
    }
}
