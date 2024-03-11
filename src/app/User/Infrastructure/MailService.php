<?php

namespace App\User\Infrastructure;

use App\User\Application\MailServiceInterface;
use App\User\Domain\Entity\AvailableLanguage;
use App\User\Infrastructure\Mail\Confirm;
use App\User\Infrastructure\Mail\ResetPassword;
use Illuminate\Support\Facades\Mail;

class MailService implements MailServiceInterface
{
    public function sendActivationCode(string $code, AvailableLanguage $language, string $notificationEmail): void
    {
        $email = new Confirm($code, $language->value);
        $email->subject(__('translations.confirmEmail', [], $language->value));
        Mail::to($notificationEmail)->send($email);
    }

    public function sendResetPassword(string $code, AvailableLanguage $language, string $notificationEmail): void
    {
        $email = new ResetPassword($code, $language->value);
        $email->subject(__('translations.resetPasswordSubject', [], $language->value));
        Mail::to($notificationEmail)->send($email);
    }
}
