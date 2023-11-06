<?php

namespace App\User\Application\Command\SendConfirmEmail;

use App\Shared\Application\Command\CommandHandler;
use App\User\Application\Query\GetLoggedUser\GetLoggedUser;
use App\User\Domain\Entity\User;
use App\User\Domain\Event\RequestedForActivationCode;

class SendConfirmHandler extends CommandHandler
{
    public function handle(SendConfirmEmail $command): bool
    {
        /** @var User $user */
        $user = $this->queryBus->handle(new GetLoggedUser());
        if ($user->hasConfirmedEmail()) {
            return false;
        }
        $this->eventEmitter->emit(RequestedForActivationCode::fromUserData($user));

        return true;
    }
}
