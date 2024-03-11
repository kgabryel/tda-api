<?php

namespace App\User\Domain\Entity;

use App\Shared\Application\UuidInterface;
use App\Shared\Domain\Entity\UserId;
use App\User\Domain\Exception\UserException;
use App\User\Domain\PasswordService;

class User
{
    private UserId $userId;
    private ?string $notificationEmail;
    private AvailableLanguage $notificationLanguage;
    private ?string $activationCode;
    private bool $emailVerified;
    private UserType $userType;
    private string $password;

    public function __construct(
        UserId $userId,
        ?string $notificationEmail,
        AvailableLanguage $notificationLanguage,
        ?string $activationCode,
        bool $emailVerified,
        UserType $userType,
        string $password
    ) {
        $this->userId = $userId;
        $this->notificationEmail = $notificationEmail;
        $this->notificationLanguage = $notificationLanguage;
        $this->activationCode = $activationCode;
        $this->emailVerified = $emailVerified;
        $this->userType = $userType;
        $this->password = $password;
    }

    public function getUserType(): UserType
    {
        return $this->userType;
    }

    public function getNotificationEmail(): ?string
    {
        return $this->notificationEmail;
    }

    public function getNotificationLanguage(): AvailableLanguage
    {
        return $this->notificationLanguage;
    }

    public function getActivationCode(): ?string
    {
        return $this->activationCode;
    }

    public function changeNotificationLanguage(AvailableLanguage $language): bool
    {
        if ($this->notificationLanguage === $language) {
            return false;
        }

        $this->notificationLanguage = $language;

        return true;
    }

    public function changeNotificationEmail(?string $email, UuidInterface $uuid): bool
    {
        if ($this->notificationEmail === $email) {
            return false;
        }
        $this->notificationEmail = $email;
        $this->emailVerified = false;
        $this->activationCode = $uuid->getValue();

        return true;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function confirmEmail(string $code): bool
    {
        if ($this->emailVerified) {
            return false;
        }
        if ($this->activationCode !== $code) {
            return false;
        }
        $this->activationCode = null;
        $this->emailVerified = true;

        return true;
    }

    public function hasConfirmedEmail(): bool
    {
        return $this->emailVerified;
    }

    public function checkPassword(string $password, PasswordService $passwordService): bool
    {
        if ($this->userType === UserType::FB_ACCOUNT) {
            return false;
        }

        return $passwordService->check($password, $this->password);
    }

    public function changePassword(string $password, PasswordService $passwordService): void
    {
        if ($this->userType === UserType::FB_ACCOUNT) {
            throw new UserException('Cannot change password for FB user.');
        }
        $this->password = $passwordService->hashPassword($password);
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
