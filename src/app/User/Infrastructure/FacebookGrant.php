<?php

namespace App\User\Infrastructure;

use App\Core\Cqrs\QueryBus;
use App\User\Application\Query\SearchByFacebookId\SearchByFacebookId;
use App\User\Domain\Entity\FacebookId;
use DateInterval;
use Laravel\Passport\Bridge\User;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Grant\AbstractGrant;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use League\OAuth2\Server\ResponseTypes\ResponseTypeInterface;
use Psr\Http\Message\ServerRequestInterface;

class FacebookGrant extends AbstractGrant
{
    private QueryBus $queryBus;

    public function __construct(RefreshTokenRepositoryInterface $refreshTokenRepository, QueryBus $queryBus)
    {
        $this->setRefreshTokenRepository($refreshTokenRepository);
        $this->refreshTokenTTL = new DateInterval('P1M');
        $this->queryBus = $queryBus;
    }

    public function respondToAccessTokenRequest(
        ServerRequestInterface $request,
        ResponseTypeInterface $responseType,
        DateInterval $accessTokenTTL
    ): ResponseTypeInterface {
        // Validate request
        $client = $this->validateClient($request);
        $scopes = $this->validateScopes($this->getRequestParameter('scope', $request));
        $user = $this->validateUser($request);
        // Finalize the requested scopes
        $scopes = $this->scopeRepository->finalizeScopes(
            $scopes,
            $this->getIdentifier(),
            $client,
            $user->getIdentifier()
        );
        // Issue and persist new tokens
        $accessToken = $this->issueAccessToken(
            $accessTokenTTL,
            $client,
            $user->getIdentifier(),
            $scopes
        );
        /** @var RefreshTokenEntityInterface $refreshToken */
        $refreshToken = $this->issueRefreshToken($accessToken);
        // Inject tokens into response
        $responseType->setAccessToken($accessToken);
        $responseType->setRefreshToken($refreshToken);

        return $responseType;
    }

    protected function validateUser(ServerRequestInterface $request): User
    {
        $facebookId = $this->getRequestParameter('id', $request);
        if (is_null($facebookId)) {
            throw OAuthServerException::invalidRequest('id');
        }
        $user = $this->queryBus->handle(new SearchByFacebookId(new FacebookId($facebookId)));
        if ($user === null) {
            throw OAuthServerException::invalidRequest('id');
        }

        return new User($user->getUserId());
    }

    public function getIdentifier(): string
    {
        return 'facebook';
    }
}
