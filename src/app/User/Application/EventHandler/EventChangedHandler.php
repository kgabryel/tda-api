<?php

namespace App\User\Application\EventHandler;

use App\Core\Cqrs\ListenEvent;
use App\User\Domain\Event\EmailChanged;

#[ListenEvent(EmailChanged::class)]
class EventChangedHandler extends EventHandler
{
    public function handle(EmailChanged $event): void
    {
        $this->userManager->changeEmail($event->getUserId(), $event->getEmail(), $event->getActivationCode());
    }
}
