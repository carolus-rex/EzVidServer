<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

use App\Jobs\Deb;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\ClientException;

class RefreshTokenRedirect
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function handle($request, Closure $next)
    {
        dispatch(new Deb('RefreshTokenExecute'));
        dispatch(new Deb($request->url()));
        dispatch(new Deb('This is the refresh token'));
        dispatch(new Deb($request->cookie('refreshToken')));
        if (Auth::check() or ($request->routeIs('login.refresh'))) {
            // we have a user or we want to refresh, continue without problems
            return $next($request);
        } else {
            // if we dont have a user but have a refresh token,
            // refresh it
            if ($request->cookie('refreshToken')){
                return $next($this->refresh($request));
            } else {
                //we dont have anything, continue normally
                return $next($request);
            }
        }
    }

    public function refresh($request)
    {
        $data = ['refresh_token' => $request->cookie("refreshToken"),
                 'client_id'     => env('PASSWORD_CLIENT_ID'),
                 'client_secret' => env('PASSWORD_CLIENT_SECRET'),
                 'grant_type'    => "refresh_token"]; //No Typo

        dispatch(new Deb("Will call guzzle"));
        $client = new Client();

        try {
            $res = $client->request('POST',
                                    url("oauth/token"),
                                    ['form_params' => $data]);

        } catch (ServerException $e) { // 500
            // if something is wrong lets die or something, continue...
            return $request;
        } catch (ClientException $e) { // 400
            // if the refresh_token is expired, continue...
            // TODO:
            // we will handle this in a diferent middleware or something
            return $request;
        }

        $cookies = json_decode((string)($res->getBody()));
        dispatch(new Deb("RESPONSE BODY"));
        dispatch(new Deb($cookies->access_token));
        dispatch(new Deb($cookies->refresh_token));

        //SET the access_token COOKIE in the CURRENT REQUEST
        $request->cookies->set('access_token', $cookies->access_token);

        Cookie::queue(cookie('access_token',
                             $cookies->access_token,
                             // passport returns time in seconds and
                             // cookie() expects time in minutes
                             $cookies->expires_in / 60,  
                             null,                                                
                             null,
                             false,
                             true)); // Http only

        Cookie::queue(cookie('refreshToken',
                             $cookies->refresh_token,
                             14400, // 10 days
                             null,
                             null,
                             false,
                             true)); // Http only

        return $request;
    }
}
