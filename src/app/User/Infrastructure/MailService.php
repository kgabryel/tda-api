<?php

namespace App\User\Infrastructure;

use App\Shared\Application\Service\TranslationServiceInterface;
use App\User\Application\MailServiceInterface;
use App\User\Domain\Entity\AvailableLanguage;
use App\User\Infrastructure\Mail\Confirm;
use App\User\Infrastructure\Mail\ResetPassword;
use Illuminate\Support\Facades\Mail;

class MailService implements MailServiceInterface
{
    private TranslationServiceInterface $translationService;

    public function __construct(TranslationServiceInterface $translationService)
    {
        $this->translationService = $translationService;
    }

    public function sendActivationCode(string $code, AvailableLanguage $language, string $notificationEmail): void
    {
        $email = new Confirm($code, $language->value);
        $email->subject($this->translationService->getTranslation('translations.confirmEmail', $language));
        Mail::to($notificationEmail)->send($email);
    }

    public function sendResetPassword(string $code, AvailableLanguage $language, string $notificationEmail): void
    {
        $email = new ResetPassword($code, $language->value);
        $email->subject($this->translationService->getTranslation('translations.resetPasswordSubject', $language));
        Mail::to($notificationEmail)->send($email);
    }
}
