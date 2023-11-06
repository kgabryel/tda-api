<?php

namespace App\User\Application\EventHandler;

use App\Core\Cqrs\ListenEvent;
use App\User\Application\MailServiceInterface;
use App\User\Domain\Event\RequestedForPasswordReset;

#[ListenEvent(RequestedForPasswordReset::class)]
class RequestedForPasswordResetHandler
{
    private MailServiceInterface $mailService;

    public function __construct(MailServiceInterface $mailService)
    {
        $this->mailService = $mailService;
    }

    public function handle(RequestedForPasswordReset $event): void
    {
        $this->mailService->sendResetPassword($event->getCode(), $event->getLanguage(), $event->getEmail());
    }
}
