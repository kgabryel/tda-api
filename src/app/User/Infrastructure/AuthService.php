<?php

namespace App\User\Infrastructure;

use Illuminate\Http\JsonResponse;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class AuthService
{
    private AuthorizationServer $server;
    private Response $response;
    private JsonResponse $jsonResponse;

    public function __construct(AuthorizationServer $server, Response $response, JsonResponse $jsonResponse)
    {
        $this->server = $server;
        $this->response = $response;
        $this->jsonResponse = $jsonResponse;
    }

    public function getTokens(ServerRequestInterface $auth): JsonResponse|ResponseInterface
    {
        try {
            return $this->server->respondToAccessTokenRequest($auth, $this->response);
        } catch (OAuthServerException $e) {
            return $this->jsonResponse->setData(['message' => $e->getMessage()])
                ->setStatusCode(ResponseAlias::HTTP_UNAUTHORIZED);
        }
    }
}
