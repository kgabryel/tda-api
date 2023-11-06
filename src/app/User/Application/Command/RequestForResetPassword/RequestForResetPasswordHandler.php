<?php

namespace App\User\Application\Command\RequestForResetPassword;

use App\Core\Cqrs\EventEmitter;
use App\Core\Cqrs\QueryBus;
use App\Shared\Application\Command\CommandHandler;
use App\User\Application\TokenService;
use App\User\Application\UserManagerInterface;
use App\User\Domain\Event\RequestedForPasswordReset;

class RequestForResetPasswordHandler extends CommandHandler
{
    private UserManagerInterface $userManager;
    private TokenService $tokenService;

    public function __construct(
        QueryBus $queryBus,
        EventEmitter $eventEmitter,
        UserManagerInterface $userManager,
        TokenService $tokenService
    ) {
        parent::__construct($queryBus, $eventEmitter);
        $this->eventEmitter = $eventEmitter;
        $this->userManager = $userManager;
        $this->tokenService = $tokenService;
    }

    public function handle(RequestForResetPassword $command): void
    {
        $token = $this->tokenService->getToken();
        $this->userManager->addResetPasswordToken($token, $command->getEmail());
        $this->eventEmitter->emit(new RequestedForPasswordReset($command->getEmail(), $command->getLanguage(), $token));
    }
}
