<?php

namespace App\User\Infrastructure\Request;

use App\Shared\Infrastructure\Request\BasicRequest;

class PushSubscriptionRequest extends BasicRequest
{
    public function rules(): array
    {
        return [
            'endpoint' => 'required|string',
            'keys' => 'required|array',
            'keys.p256dh' => 'required|string',
            'keys.auth' => 'required|string'
        ];
    }

    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    public function getAuth(): string
    {
        return $this->keys['auth'];
    }

    public function getP256dh(): string
    {
        return $this->keys['p256dh'];
    }
}
