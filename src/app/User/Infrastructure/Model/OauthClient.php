<?php

namespace App\User\Infrastructure\Model;

use Illuminate\Database\Eloquent\Model;

class OauthClient extends Model
{
    protected $table = 'oauth_clients';

    public function getId(): int
    {
        return $this->id;
    }

    public function getSecret(): string
    {
        return $this->secret;
    }
}
