<?php

namespace App\User\Application;

use App\Shared\Domain\Entity\UserId;
use App\User\Domain\Entity\SettingsKey;

interface SettingsManagerInterface
{
    public function changeValue(UserId $userId, SettingsKey $key, mixed $value): void;
}
