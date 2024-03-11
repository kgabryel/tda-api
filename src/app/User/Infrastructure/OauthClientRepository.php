<?php

namespace App\User\Infrastructure;

use App\User\Infrastructure\Model\OauthClient;

abstract class OauthClientRepository
{
    public static function findByName(string $name): OauthClient
    {
        return OauthClient::where('name', '=', $name)->firstOrFail();
    }
}
