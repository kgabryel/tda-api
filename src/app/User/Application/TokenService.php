<?php

namespace App\User\Application;

interface TokenService
{
    public function getToken(): string;
}
