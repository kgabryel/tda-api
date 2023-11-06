<?php

namespace App\User\Application\Command\Register;

use App\Core\Cqrs\EventEmitter;
use App\Core\Cqrs\QueryBus;
use App\Shared\Application\Command\CommandHandler;
use App\User\Application\UserManagerInterface;
use App\User\Domain\Event\RequestedForActivationCode;
use App\User\Domain\PasswordService;

class RegisterHandler extends CommandHandler
{
    private UserManagerInterface $userManager;
    private PasswordService $passwordService;

    public function __construct(
        QueryBus $queryBus,
        EventEmitter $eventEmitter,
        UserManagerInterface $userManager,
        PasswordService $passwordService
    ) {
        parent::__construct($queryBus, $eventEmitter);
        $this->userManager = $userManager;
        $this->passwordService = $passwordService;
    }

    public function handle(Register $command): void
    {
        $user = $this->userManager->register(
            $command->getEmail(),
            $command->getPassword(),
            $command->getLanguage(),
            $this->passwordService
        );
        $this->eventEmitter->emit(RequestedForActivationCode::fromUserData($user));
    }
}
