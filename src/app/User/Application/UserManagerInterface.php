<?php

namespace App\User\Application;

use App\Shared\Application\UuidInterface;
use App\Shared\Domain\Entity\UserId;
use App\User\Domain\Entity\AvailableLanguage;
use App\User\Domain\Entity\FacebookId;
use App\User\Domain\Entity\User;
use App\User\Domain\PasswordService;

interface UserManagerInterface
{
    public function changeEmail(UserId $userId, ?string $email, string $activationCode): void;

    public function changePassword(UserId $userId, string $newPassword): void;

    public function confirmEmail(UserId $userId): void;

    public function register(
        string $email,
        string $password,
        AvailableLanguage $language,
        PasswordService $passwordService,
        UuidInterface $uuid
    ): User;

    public function registerViaFb(
        FacebookId $facebookId,
        AvailableLanguage $language,
        PasswordService $passwordService
    ): User;

    public function addResetPasswordToken(string $token, string $email): void;

    public function addSubscription(UserId $userId, string $endpoint, string $auth, string $p256dh): bool;
}
