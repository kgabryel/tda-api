<?php

namespace App\Shared\Application\Service;

use App\Shared\Domain\Entity\AlarmId;

interface WebPushServiceInterface
{
    public function addWelcomeNotification(string $endpoint, string $auth, string $p256dh, string $lang): self;

    public function addNotification(
        string $endpoint,
        string $auth,
        string $p256dh,
        string $lang,
        string $name,
        ?string $content,
        ?string $deactivationCode,
        AlarmId $alarmId
    ): self;

    public function send(): void;
}
