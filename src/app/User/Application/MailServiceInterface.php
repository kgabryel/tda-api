<?php

namespace App\User\Application;

use App\User\Domain\Entity\AvailableLanguage;

interface MailServiceInterface
{
    public function sendActivationCode(string $code, AvailableLanguage $language, string $notificationEmail): void;

    public function sendResetPassword(string $code, AvailableLanguage $language, string $notificationEmail): void;
}
