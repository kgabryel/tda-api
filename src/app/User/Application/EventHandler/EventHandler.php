<?php

namespace App\User\Application\EventHandler;

use App\User\Application\UserManagerInterface;

abstract class EventHandler
{
    protected UserManagerInterface $userManager;

    public function __construct(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }
}
