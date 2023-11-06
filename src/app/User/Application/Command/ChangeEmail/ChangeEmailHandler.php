<?php

namespace App\User\Application\Command\ChangeEmail;

use App\Core\Cqrs\EventEmitter;
use App\Core\Cqrs\QueryBus;
use App\Shared\Application\Command\CommandHandler;
use App\Shared\Application\UuidInterface;
use App\User\Application\Query\GetLoggedUser\GetLoggedUser;
use App\User\Domain\Event\EmailChanged;

class ChangeEmailHandler extends CommandHandler
{
    private UuidInterface $uuid;

    public function __construct(QueryBus $queryBus, EventEmitter $eventEmitter, UuidInterface $uuid)
    {
        parent::__construct($queryBus, $eventEmitter);
        $this->uuid = $uuid;
    }

    public function handle(ChangeEmail $command): void
    {
        $user = $this->queryBus->handle(new GetLoggedUser());
        if ($user->changeNotificationEmail($command->getEmail(), $this->uuid)) {
            $this->eventEmitter->emit(EmailChanged::fromUserData($user));
        }
    }
}
