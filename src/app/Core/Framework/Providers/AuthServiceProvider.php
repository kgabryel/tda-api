<?php

namespace App\Core\Framework\Providers;

use App\Core\Cqrs\QueryBus;
use App\User\Infrastructure\FacebookGrant;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Bridge\RefreshTokenRepository;
use Laravel\Passport\Passport;
use League\OAuth2\Server\AuthorizationServer;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerPolicies();
        Passport::tokensExpireIn(now()->addDays(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));
        app(AuthorizationServer::class)->enableGrantType(
            $this->makeFacebookGrant(),
            Passport::tokensExpireIn()
        );
    }

    protected function makeFacebookGrant(): FacebookGrant
    {
        $grant = new FacebookGrant(
            $this->app->make(RefreshTokenRepository::class),
            $this->app->make(QueryBus::class)
        );
        $grant->setRefreshTokenTTL(Passport::refreshTokensExpireIn());

        return $grant;
    }
}
