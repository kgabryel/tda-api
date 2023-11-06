<?php

namespace App\User\Infrastructure;

use App\Shared\Domain\Entity\UserId;
use App\Shared\Infrastructure\Utils\UserUtils;
use App\User\Application\ReadRepository as UserReadRepositoryInterface;
use App\User\Application\ViewModel\EmailState;
use App\User\Application\ViewModel\Settings;
use App\User\Domain\Entity\SettingsKey;

class UserReadRepository implements UserReadRepositoryInterface
{
    public function getEmailState(UserId $userId): EmailState
    {
        $user = UserUtils::getLoggedUser();

        return new EmailState($user->getNotificationEmail(), $user->getEmailVerifiedAt() !== null);
    }

    public function getSettings(UserId $userId): Settings
    {
        $user = UserUtils::getLoggedUser();

        $settings = $user->getSettings();
        $settingsModel = new Settings();
        $settingsModel->addValue('fbAccount', $user->getFbId() !== null)
            ->setPushNotificationKey(env('WEB_NOTIFICATIONS_PUB'));
        foreach (SettingsKey::cases() as $key) {
            $fieldName = StringUtils::toCamelCase($key->value);
            $method = sprintf('get%s', ucfirst($fieldName));
            $settingsModel->addValue($key->value, $settings->$method());
        }

        return $settingsModel;
    }
}
