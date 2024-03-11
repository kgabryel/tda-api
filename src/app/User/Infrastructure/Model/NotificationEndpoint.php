<?php

namespace App\User\Infrastructure\Model;

use App\Shared\Domain\Entity\UserId;
use Illuminate\Database\Eloquent\Model;

class NotificationEndpoint extends Model
{
    protected $table = 'notifications_endpoints';

    public function setUserId(UserId $userId): self
    {
        $this->user_id = $userId->getValue();

        return $this;
    }

    public function setEndpoint(string $endpoint): self
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    public function setAuth(string $auth): self
    {
        $this->auth = $auth;

        return $this;
    }

    public function setP256dh(string $p256dh): self
    {
        $this->p256dh = $p256dh;

        return $this;
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
