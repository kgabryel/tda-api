<?php

namespace App\User\Infrastructure;

use App\User\Application\TokenService as TokenServiceInterface;
use Illuminate\Support\Str;

class TokenService implements TokenServiceInterface
{
    public function getToken(): string
    {
        return Str::random(100);
    }
}
