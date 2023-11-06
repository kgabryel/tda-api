<?php

namespace App\User\Application\Command\ResetPassword;

use App\Core\Cqrs\EventEmitter;
use App\Core\Cqrs\QueryBus;
use App\Shared\Application\Command\CommandHandler;
use App\User\Application\Query\FindByResetPasswordCode\FindByResetPasswordCode;
use App\User\Domain\Entity\User;
use App\User\Domain\Event\PasswordChanged;
use App\User\Domain\PasswordService;

class ResetPasswordHandler extends CommandHandler
{
    private PasswordService $passwordService;

    public function __construct(QueryBus $queryBus, EventEmitter $eventEmitter, PasswordService $passwordService)
    {
        parent::__construct($queryBus, $eventEmitter);
        $this->passwordService = $passwordService;
    }

    public function handle(ResetPassword $command): void
    {
        /** @var User $user */
        $user = $this->queryBus->handle(new FindByResetPasswordCode($command->getCode()));
        $user->changePassword($command->getPassword(), $this->passwordService);
        $this->eventEmitter->emit(new PasswordChanged($user->getUserId(), $user->getPassword()));
    }
}
