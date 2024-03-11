<?php

namespace App\User\Application;

use App\Shared\Domain\Entity\UserId;
use App\User\Application\ViewModel\EmailState;
use App\User\Application\ViewModel\Settings;

interface ReadRepository
{
    public function getEmailState(UserId $userId): EmailState;

    public function getSettings(UserId $userId): Settings;
}
