<?php

namespace App\User\Infrastructure;

use App\Shared\Domain\Entity\UserId;
use App\User\Application\SettingsManagerInterface;
use App\User\Domain\Entity\SettingsKey;
use App\User\Infrastructure\Model\Settings;

class SettingsManager implements SettingsManagerInterface
{
    public function changeValue(UserId $userId, SettingsKey $key, mixed $value): void
    {
        $settings = $this->getModel($userId);
        $method = sprintf('set%s', ucfirst(StringUtils::toCamelCase($key->value)));
        $settings->$method($value);
        $settings->update();
    }

    private function getModel(UserId $userId): Settings
    {
        return Settings::where('user_id', '=', $userId->getValue())->firstOrFail();
    }
}
