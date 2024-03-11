<?php

namespace App\User\Infrastructure\Controller;

use App\Core\Cqrs\CommandBus;
use App\Core\Cqrs\QueryBus;
use App\Shared\Infrastructure\BaseController;
use App\User\Application\Command\Register\Register;
use App\User\Application\Command\RegisterViaFb\RegisterViaFb;
use App\User\Application\Command\RequestForResetPassword\RequestForResetPassword;
use App\User\Application\Command\ResetPassword\ResetPassword;
use App\User\Application\Query\SearchByFacebookId\SearchByFacebookId;
use App\User\Domain\Entity\FacebookId;
use App\User\Infrastructure\AuthRequestFactory;
use App\User\Infrastructure\AuthService;
use App\User\Infrastructure\LangUtils;
use App\User\Infrastructure\Request\LoginRequest;
use App\User\Infrastructure\Request\RefreshTokenRequest;
use App\User\Infrastructure\Request\RegistrationRequest;
use App\User\Infrastructure\Request\ResetPasswordRequest;
use App\User\Infrastructure\Request\ResetTokenRequest;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Socialite\Facades\Socialite;
use Psr\Http\Message\ResponseInterface;

class AuthController extends BaseController
{
    private AuthService $authService;
    private AuthRequestFactory $factory;

    public function __construct(
        QueryBus $queryBus,
        CommandBus $commandBus,
        Response $response,
        AuthService $authService,
        AuthRequestFactory $factory
    ) {
        parent::__construct($queryBus, $commandBus, $response);
        $this->authService = $authService;
        $this->factory = $factory;
    }

    public function register(RegistrationRequest $request): JsonResponse|ResponseInterface
    {
        $email = $request->getEmail();
        $password = $request->getPassword();
        $this->commandBus->handle(new Register($email, $password, $request->getLang()));

        return $this->authService->getTokens($this->factory->getPasswordGrant($email, $password));
    }

    public function login(LoginRequest $request): JsonResponse|ResponseInterface
    {
        return $this->authService->getTokens(
            $this->factory->getPasswordGrant($request->getEmail(), $request->getPassword())
        );
    }

    public function refreshToken(RefreshTokenRequest $request): JsonResponse|ResponseInterface
    {
        return $this->authService->getTokens($this->factory->getRefreshTokenGrant($request->getToken()));
    }

    public function facebookLogin(Request $request): JsonResponse|ResponseInterface|Response
    {
        try {
            $facebookUser = Socialite::with('facebook')
                ->stateless()
                ->user();
        } catch (Exception) {
            return $this->response->setStatusCode(Response::HTTP_UNAUTHORIZED);
        }
        $user = $this->queryBus->handle(new SearchByFacebookId(new FacebookId($facebookUser->id)));
        if ($user === null) {
            $this->commandBus->handle(
                new RegisterViaFb(new FacebookId($facebookUser->id), LangUtils::getLang($request->get('lang')))
            );
        }

        return $this->authService->getTokens($this->factory->getFacebookGrant($facebookUser->id));
    }

    public function getRedirectUrl(): RedirectResponse
    {
        return Socialite::with('facebook')
            ->stateless()
            ->redirect();
    }

    public function sendResetPasswordEmail(ResetTokenRequest $request): Response
    {
        $this->commandBus->handle(
            new RequestForResetPassword($request->getEmail(), LangUtils::getLang($request->get('lang')))
        );

        return $this->response->setStatusCode(Response::HTTP_NO_CONTENT);
    }

    public function resetPassword(ResetPasswordRequest $request): Response
    {
        $this->commandBus->handle(new ResetPassword($request->getToken(), $request->getPassword()));

        return $this->response->setStatusCode(Response::HTTP_NO_CONTENT);
    }
}
