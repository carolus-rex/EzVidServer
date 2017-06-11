<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Request;
use App\Auth\Exceptions\InvalidCredentialsException;

use Illuminate\Foundation\Application;

use App\Jobs\Deb;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;

use Illuminate\Support\Facades\Crypt;
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
    //public $loginProxy;
    public $access_token;
    public $refreshToken;
    public $request;

    public function __construct(Application $app)
    {  
        $this->apiConsumer = $app->make('apiconsumer');
    }

    public function handle($request, Closure $next)
    {
        dispatch(new Deb('RefreshTokenExecute'));
        dispatch(new Deb($request->url()));
        dispatch(new Deb('This is the refresh token'));
        dispatch(new Deb($request->cookie('refreshToken')));
        if (Auth::check()){
            // we have a user, continue without problems
            return $next($request);
        } else {
            // if we dont have a user but have a refresh token, refresh it
            // remember to block refresh on login.refresh
            if ($request->cookie('refreshToken')){
                //collect new session_data
                if ($request->url() === route('login.refresh')){
                    dispatch(new Deb("we are login/refresh, lets continue"));
                    $response =  $next($request);
                    dispatch(new Deb("we are login/refresh, WE ARE RESPONDING"));
                    return $response;
                } else{
                //forward the request to the next middleware and then append the new cookie to the response;
                return $next($this->refresh($request))->cookie($this->access_token)
                                                      ->cookie($this->refreshToken);
                }
            } else {
                //we dont have anything, so continue normally
                return $next($request);
            }
        }
    }

    public function refresh(Request $request){
        $this->request = $request;

        //$data = 
        $data = ['refresh_token' => $request->cookie("refreshToken"),
            'client_id'     => env('PASSWORD_CLIENT_ID'),
            'client_secret' => env('PASSWORD_CLIENT_SECRET'),
            'grant_type'    => "refresh_token"
        ];

        dispatch(new Deb("Will call guzzle"));
        $client = new Client();
        //add error handling
        $res = $client->request('POST', "http://test.gob.dp/oauth/token", [
            'allow_redirects' => false,
            'debug' => true,
            'form_params'=>$data
        ]);


        $cookies = json_decode((string)($res->getBody()));
        dispatch(new Deb("RESPONSE BODY"));
        dispatch(new Deb($cookies->access_token));
        dispatch(new Deb($cookies->refresh_token));
        //dispatch(new Deb((string)($res->getBody())));

        $this->request->cookies->set('access_token', $cookies->access_token);

        $this->access_token = cookie('access_token',
                                  $cookies->access_token,
                                  1,
                                  null,
                                  null,
                                  false,
                                  true);
        $this->refreshToken = cookie('refreshToken',
                                  $cookies->refresh_token,
                                  14400,
                                  null,
                                  null,
                                  false,
                                  true);
        return $this->request;


        //$response = $this->apiConsumer->post('/oauth/token', $data);

        /*$cookieJar = CookieJar::fromArray(['refreshToken' => Crypt::encrypt($request->cookie('refreshToken'))], 'test.gob.dp');

        
        dispatch(new Deb("Will call guzzle"));
        dispatch(new Deb(route('login.refresh')));
        $client = new Client();
        try {
            $res = $client->request('GET', route('login.refresh'), [
                'cookies' => $cookieJar,
                'allow_redirects' => false,
                'debug' => true
        } catch (ServerException $e) {
            dispatch(new Deb('WE GOT A 500'));
            echo (string)($e->getResponse()->getBody());
        } catch (ClientException $e){
            dispatch(new Deb('WE GOT A 400'));
            echo (string)($e->getResponse()->getBody());
        }
        



        dispatch(new Deb("headers"));
        foreach($res->getHeaders() as $name=>$values){
            dispatch(new Deb($name));
            dispatch(new Deb($values));
        }

        dispatch(new Deb("rawheaders"));
        dispatch(new Deb($res->getRawHeaders()));*/
        // "200"
        //echo $res->getHeader('content-type');
        // 'application/json; charset=utf8'
        //echo $res->getBody();
        // {"type":"User"...'

        /*$session_data = $this->attemptRefresh();

        //modify the request with the new access_token
        $this->request->cookies->set('access_token', $session_data['access_token']);

        $this->access_token = cookie('access_token',
                                  $session_data['access_token'],
                                  10,
                                  null,
                                  null,
                                  false,
                                  true);
        $this->refreshToken = cookie('refreshToken',
                                  $session_data['refreshToken'],
                                  14400,
                                  null,
                                  null,
                                  false,

        $request = $this->request;

        return $request;*/
    }

    public function attemptRefresh()
    {
        $refreshToken = $this->request->cookie('refreshToken');

        return $this->proxy('refresh_token', [
            'refresh_token' => $refreshToken
        ]);
    }

    /**
     * Proxy a request to the OAuth server.
     *
     * @param string $grantType what type of grant type should be proxied
     * @param array $data the data to send to the server
     */
    public function proxy($grantType, array $data = [])
    {
        $data = array_merge($data, [
            'client_id'     => env('PASSWORD_CLIENT_ID'),
            'client_secret' => env('PASSWORD_CLIENT_SECRET'),
            'grant_type'    => $grantType
        ]);

        $response = $this->apiConsumer->post('/oauth/token', $data);

        if (!$response->isSuccessful()) {
            throw new InvalidCredentialsException();
        }

        $data = json_decode($response->getContent());

        // Create a refresh token cookie
        
        return [
            'access_token' => $data->access_token,
            'expires_in' => $data->expires_in,
            'refreshToken' => $data->refresh_token
        ];
    }
}
