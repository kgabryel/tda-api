<?php

namespace App\User\Application\EventHandler;

use App\Core\Cqrs\ListenEvent;
use App\User\Domain\Event\PasswordChanged;

#[ListenEvent(PasswordChanged::class)]
class PasswordChangedHandler extends EventHandler
{
    public function handle(PasswordChanged $event): void
    {
        $this->userManager->changePassword($event->getUserId(), $event->getPassword());
    }
}
