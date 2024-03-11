<?php

namespace App\User\Application\Command\ChangePassword;

use App\Core\Cqrs\EventEmitter;
use App\Core\Cqrs\QueryBus;
use App\Shared\Application\Command\CommandHandler;
use App\User\Application\Query\GetLoggedUser\GetLoggedUser;
use App\User\Domain\Entity\User;
use App\User\Domain\Event\PasswordChanged;
use App\User\Domain\PasswordService;

class ChangePasswordHandler extends CommandHandler
{
    private PasswordService $passwordService;

    public function __construct(QueryBus $queryBus, EventEmitter $eventEmitter, PasswordService $passwordService)
    {
        parent::__construct($queryBus, $eventEmitter);
        $this->passwordService = $passwordService;
    }

    public function handle(ChangePassword $command): bool
    {
        /** @var User $user */
        $user = $this->queryBus->handle(new GetLoggedUser());
        if (!$user->checkPassword($command->getOldPassword(), $this->passwordService)) {
            return false;
        }
        $user->changePassword($command->getNewPassword(), $this->passwordService);
        $this->eventEmitter->emit(new PasswordChanged($user->getUserId(), $user->getPassword()));

        return true;
    }
}
