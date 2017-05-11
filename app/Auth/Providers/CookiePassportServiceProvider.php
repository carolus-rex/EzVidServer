<?php

namespace App\Auth\Providers;

use Laravel\Passport\PassportServiceProvider;
use Illuminate\Auth\RequestGuard;

use App\Auth\Guards\CookieTokenGuard; // IMPORTANT

use App\Auth\Servers\CookieResourceServer;

use Illuminate\Support\Facades\Auth;
use Laravel\Passport\TokenRepository;
use Laravel\Passport\ClientRepository;

use Laravel\Passport\Bridge;

use Laravel\Passport\Passport;

class CookiePassportServiceProvider extends PassportServiceProvider
{
    /**
     * Make an instance of the token guard.
     *
     * @param  array  $config
     * @return RequestGuard
     */
    protected function makeGuard(array $config)
    {
        return new RequestGuard(function ($request) use ($config) {
            return (new CookieTokenGuard(
                $this->app->make(CookieResourceServer::class),  // IMPORTANT
                Auth::createUserProvider($config['provider']),
                new TokenRepository,
                $this->app->make(ClientRepository::class),
                $this->app->make('encrypter')
            ))->user($request);
        }, $this->app['request']);
    }

    protected function registerResourceServer()
    {
        // DON'T TOUCH THIS. It may seem like unnecesary, but it is very important
        $this->app->singleton(CookieResourceServer::class, function () {
            return new CookieResourceServer(
                $this->app->make(Bridge\AccessTokenRepository::class),
                'file://'.Passport::keyPath('oauth-public.key')
            );
        });
    }

}
