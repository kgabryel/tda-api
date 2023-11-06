<?php

namespace App\User\Infrastructure;

use Psr\Http\Message\ServerRequestInterface;

use function env;

class AuthRequestFactory
{
    private ServerRequestInterface $request;
    private int $clientId;
    private string $clientSecret;

    public function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;
        $client = OauthClientRepository::findByName(env('OAUTH_PASSWORD_CLIENT'));
        $this->clientId = $client->getId();
        $this->clientSecret = $client->getSecret();
    }

    public function getPasswordGrant(string $username, string $password): ServerRequestInterface
    {
        return $this->request->withParsedBody([
            'username' => $username,
            'password' => $password,
            'grant_type' => 'password',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret
        ]);
    }

    public function getRefreshTokenGrant(string $refreshToken): ServerRequestInterface
    {
        return $this->request->withParsedBody([
            'refresh_token' => $refreshToken,
            'grant_type' => 'refresh_token',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret
        ]);
    }

    public function getFacebookGrant(string $facebookId): ServerRequestInterface
    {
        return $this->request->withParsedBody([
            'grant_type' => 'facebook',
            'id' => $facebookId,
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret
        ]);
    }
}
