<?php

namespace App\User\Domain;

interface PasswordService
{
    public function check(string $password, string $hash): bool;

    public function hashPassword(string $password): string;
}
