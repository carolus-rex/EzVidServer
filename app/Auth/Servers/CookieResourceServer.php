<?php

namespace App\Auth\Servers;

use League\OAuth2\Server\ResourceServer;

use League\OAuth2\Server\AuthorizationValidators\AuthorizationValidatorInterface;

use App\Auth\Validators\CookieBearerTokenValidator;

use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use Psr\Http\Message\ServerRequestInterface;

use League\OAuth2\Server\CryptKey;

class CookieResourceServer extends ResourceServer
{

	protected $cookieAuthorizationValidator;

    public function __construct(
        AccessTokenRepositoryInterface $accessTokenRepository,
        $publicKey,
        AuthorizationValidatorInterface $authorizationValidator = null,
        AuthorizationValidatorInterface $cookieAuthorizationValidator = null
    ) {
        $this->accessTokenRepository = $accessTokenRepository;

        if ($publicKey instanceof CryptKey === false) {
            $publicKey = new CryptKey($publicKey);
        }
        $this->publicKey = $publicKey;

        $this->authorizationValidator = $authorizationValidator;
        $this->cookieAuthorizationValidator = $cookieAuthorizationValidator;
    }

    /**
     * @return AuthorizationValidatorInterface
     */
    protected function getCookieAuthorizationValidator()
    {
        if ($this->cookieAuthorizationValidator instanceof AuthorizationValidatorInterface === false) {
            $this->cookieAuthorizationValidator = new CookieBearerTokenValidator($this->accessTokenRepository);
        }

        $this->cookieAuthorizationValidator->setPublicKey($this->publicKey);

        return $this->cookieAuthorizationValidator;
    }

    /**
     * Determine the access token validity.
     *
     * @param ServerRequestInterface $request
     *
     * @throws OAuthServerException
     *
     * @return ServerRequestInterface
     */
    public function validateCookieAuthenticatedRequest(ServerRequestInterface $request)
    {
        return $this->getCookieAuthorizationValidator()->validateAuthorization($request);
    }
}

