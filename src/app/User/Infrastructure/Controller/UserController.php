<?php

namespace App\User\Infrastructure\Controller;

use App\Shared\Infrastructure\BaseController;
use App\User\Application\Command\AddPushSubscription\AddPushSubscription;
use App\User\Application\Command\ChangeEmail\ChangeEmail;
use App\User\Application\Command\ChangePassword\ChangePassword;
use App\User\Application\Command\ChangeSettingsValue\ChangeSettingsValue;
use App\User\Application\Command\ConfirmEmail\ConfirmEmail;
use App\User\Application\Command\SendConfirmEmail\SendConfirmEmail;
use App\User\Application\Query\GetEmailState\GetEmailState;
use App\User\Application\Query\GetSettings\GetSettings;
use App\User\Application\ViewModel\EmailState;
use App\User\Application\ViewModel\Settings;
use App\User\Domain\Entity\SettingsKey;
use App\User\Infrastructure\Request\ChangeDefaultPaginationRequest;
use App\User\Infrastructure\Request\ChangeEmailRequest;
use App\User\Infrastructure\Request\ChangeNotificationLanguageRequest;
use App\User\Infrastructure\Request\ChangePasswordRequest;
use App\User\Infrastructure\Request\ChangeSettingsRequest;
use App\User\Infrastructure\Request\ConfirmEmailRequest;
use App\User\Infrastructure\Request\PushSubscriptionRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class UserController extends BaseController
{
    public function changeEmail(ChangeEmailRequest $request): RedirectResponse
    {
        $this->commandBus->handle(new ChangeEmail($request->getEmail()));

        return redirect(route('settings.getEmailState'), Response::HTTP_SEE_OTHER);
    }

    public function getEmailState(): EmailState
    {
        return $this->queryBus->handle(new GetEmailState());
    }

    public function getSettings(): Settings
    {
        return $this->queryBus->handle(new GetSettings());
    }

    public function changeSettings(string $field, ChangeSettingsRequest $request): Response
    {
        $settingsName = SettingsKey::tryFrom(str_replace('-', '_', $field));
        if ($settingsName === null) {
            return $this->response->setStatusCode(Response::HTTP_NOT_FOUND);
        }
        $this->commandBus->handle(new ChangeSettingsValue($settingsName, $request->getValue()));

        return $this->response->setStatusCode(Response::HTTP_NO_CONTENT);
    }

    public function changeDefaultPagination(ChangeDefaultPaginationRequest $request): Response
    {
        $this->commandBus->handle(new ChangeSettingsValue(SettingsKey::DEFAULT_PAGINATION, $request->getValue()));

        return $this->response->setStatusCode(Response::HTTP_NO_CONTENT);
    }

    public function changeNotificationLanguage(ChangeNotificationLanguageRequest $request): Response
    {
        $this->commandBus->handle(new ChangeSettingsValue(SettingsKey::NOTIFICATION_LANGUAGE, $request->getValue()));

        return $this->response->setStatusCode(Response::HTTP_NO_CONTENT);
    }

    public function confirmEmail(ConfirmEmailRequest $request): RedirectResponse|Response
    {
        if ($this->commandBus->handleWithResult(new ConfirmEmail($request->getCode()))) {
            return redirect(route('settings.getEmailState'), Response::HTTP_SEE_OTHER);
        }

        return $this->response->setStatusCode(Response::HTTP_BAD_REQUEST);
    }

    public function sendCode(): Response
    {
        if ($this->commandBus->handleWithResult(new SendConfirmEmail())) {
            return $this->response->setStatusCode(Response::HTTP_NO_CONTENT);
        }

        return $this->response->setStatusCode(Response::HTTP_BAD_REQUEST);
    }

    public function changePassword(ChangePasswordRequest $changePasswordRequest): Response
    {
        if (
            $this->commandBus->handleWithResult(
                new ChangePassword($changePasswordRequest->getPassword(), $changePasswordRequest->getNewPassword())
            )
        ) {
            return $this->response->setStatusCode(Response::HTTP_NO_CONTENT);
        }

        return $this->response->setStatusCode(Response::HTTP_BAD_REQUEST);
    }

    public function addPushSubscription(PushSubscriptionRequest $pushSubscriptionRequest): Response
    {
        $this->commandBus->handle(
            new AddPushSubscription(
                $pushSubscriptionRequest->getEndpoint(),
                $pushSubscriptionRequest->getAuth(),
                $pushSubscriptionRequest->getP256dh()
            )
        );

        return $this->response->setStatusCode(Response::HTTP_NO_CONTENT);
    }
}
