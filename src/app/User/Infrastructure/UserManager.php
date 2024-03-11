<?php

namespace App\User\Infrastructure;

use App\Core\Cache;
use App\Shared\Application\UuidInterface;
use App\Shared\Domain\Entity\UserId;
use App\User\Application\UserManagerInterface;
use App\User\Domain\Entity\AvailableLanguage;
use App\User\Domain\Entity\FacebookId;
use App\User\Domain\Entity\User as DomainModel;
use App\User\Domain\Entity\UserType;
use App\User\Domain\PasswordService;
use App\User\Infrastructure\Model\NotificationEndpoint;
use App\User\Infrastructure\Model\PasswordReset;
use App\User\Infrastructure\Model\Settings;
use App\User\Infrastructure\Model\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

class UserManager implements UserManagerInterface
{
    public function changeEmail(UserId $userId, ?string $email, string $activationCode): void
    {
        $this->getModel($userId)
            ->setEmailVerifiedAt(null)
            ->setNotificationEmail($email)
            ->setActivationCode($activationCode)
            ->update();
    }

    public function getModel(UserId $userId): User
    {
        $user = new User();
        $user->id = $userId->getValue();
        $user->exists = true;

        return $user;
    }

    public function confirmEmail(UserId $userId): void
    {
        $this->getModel($userId)
            ->setEmailVerifiedAt(Carbon::now())
            ->setActivationCode(null)
            ->update();
    }

    public function changePassword(UserId $userId, string $newPassword): void
    {
        $this->getModel($userId)->setPassword($newPassword)
            ->update();
    }

    public function register(
        string $email,
        string $password,
        AvailableLanguage $language,
        PasswordService $passwordService,
        UuidInterface $uuid
    ): DomainModel {
        $user = new User();
        $user->setEmail($email)
            ->setNotificationEmail($email)
            ->setPassword($passwordService->hashPassword($password))
            ->setActivationCode($uuid->getValue())
            ->save();
        $this->createSettings(new UserId($user->getId()), $language);

        return new DomainModel(
            new UserId($user->getId()),
            $user->getNotificationEmail(),
            $language,
            $user->getActivationCode(),
            $user->getEmailVerifiedAt() !== null,
            UserType::NORMAL_ACCOUNT,
            $user->getPassword()
        );
    }

    private function createSettings(UserId $userId, AvailableLanguage $language): void
    {
        $settings = new Settings();
        $settings->setNotificationLanguage($language->value)
            ->setUserId($userId)
            ->save();
    }

    public function addResetPasswordToken(string $token, string $email): void
    {
        $model = new PasswordReset();
        $model->setToken($token)
            ->setEmail($email)
            ->save();
    }

    public function registerViaFb(
        FacebookId $facebookId,
        AvailableLanguage $language,
        PasswordService $passwordService
    ): DomainModel {
        $user = new User();
        $user->setEmail(sprintf('%s@fb.com', $facebookId->getValue()))
            ->setPassword($passwordService->hashPassword(Str::random(30)))
            ->setFacebookId($facebookId->getValue())
            ->save();
        $this->createSettings(new UserId($user->getId()), $language);
        $domainModel = new DomainModel(
            new UserId($user->getId()),
            null,
            $language,
            $user->getActivationCode(),
            false,
            UserType::FB_ACCOUNT,
            $user->getPassword()
        );
        $key = self::getCacheKey($domainModel->getUserId());
        Cache::add($key, $domainModel);

        return Cache::get($key);
    }

    public static function getCacheKey(UserId $userId): string
    {
        return sprintf('users-%s', $userId);
    }

    public function addSubscription(UserId $userId, string $endpoint, string $auth, string $p256dh): bool
    {
        $notification = NotificationEndpoint::where('endpoint', '=', $endpoint)
            ->where('user_id', '=', $userId->getValue())
            ->first();
        if ($notification === null) {
            $notification = new NotificationEndpoint();
            $notification->setUserId($userId)
                ->setEndpoint($endpoint)
                ->setAuth($auth)
                ->setP256dh($p256dh)
                ->save();

            return true;
        }

        $notification->setUserId($userId)
            ->setEndpoint($endpoint)
            ->setAuth($auth)
            ->setP256dh($p256dh)
            ->update();

        return false;
    }
}
