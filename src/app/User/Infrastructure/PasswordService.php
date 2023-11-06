<?php

namespace App\User\Infrastructure;

use App\User\Domain\PasswordService as PasswordServiceInmterface;
use Illuminate\Support\Facades\Hash;

class PasswordService implements PasswordServiceInmterface
{
    public function check(string $password, string $hash): bool
    {
        return Hash::check($password, $hash);
    }

    public function hashPassword(string $password): string
    {
        return bcrypt($password);
    }
}
