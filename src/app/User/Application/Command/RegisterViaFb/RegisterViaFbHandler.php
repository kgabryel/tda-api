<?php

namespace App\User\Application\Command\RegisterViaFb;

use App\User\Application\UserManagerInterface;
use App\User\Domain\PasswordService;

class RegisterViaFbHandler
{
    private UserManagerInterface $userManager;
    private PasswordService $passwordService;

    public function __construct(UserManagerInterface $userManager, PasswordService $passwordService)
    {
        $this->userManager = $userManager;
        $this->passwordService = $passwordService;
    }

    public function handle(RegisterViaFb $command): void
    {
        $this->userManager->registerViaFb(
            $command->getFacebookId(),
            $command->getLanguage(),
            $this->passwordService
        );
    }
}
