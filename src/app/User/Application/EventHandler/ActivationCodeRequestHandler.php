<?php

namespace App\User\Application\EventHandler;

use App\Core\Cqrs\ListenEvent;
use App\User\Application\MailServiceInterface;
use App\User\Domain\Event\EmailChanged;
use App\User\Domain\Event\RequestedForActivationCode;

#[ListenEvent(EmailChanged::class)]
#[ListenEvent(RequestedForActivationCode::class)]
class ActivationCodeRequestHandler
{
    private MailServiceInterface $mailService;

    public function __construct(MailServiceInterface $mailService)
    {
        $this->mailService = $mailService;
    }

    public function handle(EmailChanged|RequestedForActivationCode $event): void
    {
        $notificationEmail = $event->getEmail();
        if ($notificationEmail === null) {
            return;
        }
        $this->mailService->sendActivationCode($event->getActivationCode(), $event->getLang(), $notificationEmail);
    }
}
