<?php

namespace App\Auth\Guards;

use Laravel\Passport\Guards\TokenGuard;

use App\Auth\Servers\CookieResourceServer;

use Illuminate\Contracts\Auth\UserProvider;
use Laravel\Passport\TokenRepository;
use Laravel\Passport\ClientRepository;
use Illuminate\Contracts\Encryption\Encrypter;

use Illuminate\Http\Request;

use Laravel\Passport\Passport;

use Illuminate\Container\Container;

use Illuminate\Contracts\Debug\ExceptionHandler;
use League\OAuth2\Server\Exception\OAuthServerException;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;


class CookieTokenGuard extends TokenGuard
{
	public function __construct(CookieResourceServer $server,	// IMPORTANT
                                UserProvider $provider,
                                TokenRepository $tokens,
                                ClientRepository $clients,
                                Encrypter $encrypter)
    {
        $this->server = $server;
        $this->tokens = $tokens;
        $this->clients = $clients;
        $this->provider = $provider;
        $this->encrypter = $encrypter;
    }

	/**
     * Get the user for the incoming request.
     *
     * @param  Request  $request
     * @return mixed
     */
    public function user(Request $request)
    {
        if ($request->bearerToken()) {
            return $this->authenticateViaBearerToken($request);
        } elseif ($request->cookie(Passport::cookie())) {
            return $this->authenticateViaCookie($request);
        } elseif($request->cookie('access_token')){
            return $this->authenticateViaAccessCookie($request);
        }
    }

    protected function authenticateViaAccessCookie($request) 
    {
		// First, we will convert the Symfony request to a PSR-7 implementation which will
        // be compatible with the base OAuth2 library. The Symfony bridge can perform a
        // conversion for us to a Zend Diactoros implementation of the PSR-7 request.
        $psr = (new DiactorosFactory)->createRequest($request);
        try {
            $psr = $this->server->validateCookieAuthenticatedRequest($psr);

            // If the access token is valid we will retrieve the user according to the user ID
            // associated with the token. We will use the provider implementation which may
            // be used to retrieve users from Eloquent. Next, we'll be ready to continue.
            $user = $this->provider->retrieveById(
                $psr->getAttribute('oauth_user_id')
            );

            if (! $user) {
                return;
            }

            // Next, we will assign a token instance to this user which the developers may use
            // to determine if the token has a given scope, etc. This will be useful during
            // authorization such as within the developer's Laravel model policy classes.
            $token = $this->tokens->find(
                $psr->getAttribute('oauth_access_token_id')
            );

            $clientId = $psr->getAttribute('oauth_client_id');

            // Finally, we will verify if the client that issued this token is still valid and
            // its tokens may still be used. If not, we will bail out since we don't want a
            // user to be able to send access tokens for deleted or revoked applications.
            if ($this->clients->revoked($clientId)) {
                return;
            }

            return $token ? $user->withAccessToken($token) : null;
        } catch (OAuthServerException $e) {
            return Container::getInstance()->make(
                ExceptionHandler::class
            )->report($e);
        }
    }
}
