<?php

namespace App\User\Application\EventHandler;

use App\Core\Cqrs\ListenEvent;
use App\User\Domain\Event\EmailConfirmed;

#[ListenEvent(EmailConfirmed::class)]
class EmailConfirmedHandler extends EventHandler
{
    public function handle(EmailConfirmed $event): void
    {
        $this->userManager->confirmEmail($event->getUserId());
    }
}
