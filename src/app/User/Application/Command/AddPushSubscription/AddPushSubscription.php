<?php

namespace App\User\Application\Command\AddPushSubscription;

use App\Core\Cqrs\CommandHandler;
use App\Shared\Application\Command\Command;

#[CommandHandler(AddPushSubscriptionHandler::class)]
class AddPushSubscription implements Command
{
    private string $endpoint;
    private string $auth;
    private string $p256dh;

    public function __construct(string $endpoint, string $auth, string $p256dh)
    {
        $this->endpoint = $endpoint;
        $this->auth = $auth;
        $this->p256dh = $p256dh;
    }

    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    public function getAuth(): string
    {
        return $this->auth;
    }

    public function getP256dh(): string
    {
        return $this->p256dh;
    }
}
