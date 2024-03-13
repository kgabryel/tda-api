<?php

namespace App\User\Infrastructure;

use App\Core\Cache;
use App\Shared\Domain\Entity\UserId;
use App\Shared\Infrastructure\Utils\UserUtils;
use App\User\Domain\Entity\AvailableLanguage;
use App\User\Domain\Entity\FacebookId;
use App\User\Domain\Entity\User;
use App\User\Domain\Entity\UserType;
use App\User\Domain\WriteRepository as UserWriteRepositoryInterface;
use App\User\Infrastructure\Model\User as UserModel;

class UserWriteRepository implements UserWriteRepositoryInterface
{
    public function getLoggedUser(): User
    {
        $loggedUser = UserUtils::getLoggedUser();
        $uId = $loggedUser->getId();

        return Cache::remember(UserManager::getCacheKey(new UserId($uId)), static function() use ($uId, $loggedUser) {
            return new User(
                new UserId($uId),
                $loggedUser->getNotificationEmail(),
                AvailableLanguage::from($loggedUser->getSettings()->getNotificationLanguage()),
                $loggedUser->getActivationCode(),
                $loggedUser->getEmailVerifiedAt() !== null,
                $loggedUser->getFbId() !== null ? UserType::FB_ACCOUNT : UserType::NORMAL_ACCOUNT,
                $loggedUser->getPassword()
            );
        });
    }

    public function findById(UserId $userId): User
    {
        $uId = $userId->getValue();

        return Cache::remember(UserManager::getCacheKey($userId), function() use ($uId) {
            return $this->toDomainModel(UserModel::findOrFail($uId));
        });
    }

    private function toDomainModel(UserModel $user): User
    {
        return new User(
            new UserId($user->getId()),
            $user->getNotificationEmail(),
            AvailableLanguage::from($user->getSettings()->getNotificationLanguage()),
            $user->getActivationCode(),
            $user->getEmailVerifiedAt() !== null,
            $user->getFbId() !== null ? UserType::FB_ACCOUNT : UserType::NORMAL_ACCOUNT,
            $user->getPassword()
        );
    }

    public function searchByFacebookId(FacebookId $facebookId): ?User
    {
        $userModel = UserModel::where('facebook_id', '=', $facebookId)->first();
        if ($userModel === null) {
            return null;
        }
        $user = $this->toDomainModel($userModel);
        $key = UserManager::getCacheKey($user->getUserId());
        Cache::add($key, $user);

        return Cache::get($key);
    }

    public function findByResetPasswordCode(string $code): User
    {
        $userModel = UserModel::join('password_resets', 'users.email', '=', 'password_resets.email')
            ->where('password_resets.token', '=', $code)
            ->firstOrFail();
        $user = $this->toDomainModel($userModel);
        $key = UserManager::getCacheKey($user->getUserId());
        Cache::add($key, $user);

        return Cache::get($key);
    }
}
