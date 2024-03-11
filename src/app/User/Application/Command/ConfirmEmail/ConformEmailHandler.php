<?php

namespace App\User\Application\Command\ConfirmEmail;

use App\Shared\Application\Command\CommandHandler;
use App\User\Application\Query\GetLoggedUser\GetLoggedUser;
use App\User\Domain\Entity\User;
use App\User\Domain\Event\EmailConfirmed;

class ConformEmailHandler extends CommandHandler
{
    public function handle(ConfirmEmail $command): bool
    {
        /** @var User $user */
        $user = $this->queryBus->handle(new GetLoggedUser());
        if (!$user->confirmEmail($command->getCode())) {
            return false;
        }
        $this->eventEmitter->emit(new EmailConfirmed($user->getUserId()));

        return true;
    }
}
