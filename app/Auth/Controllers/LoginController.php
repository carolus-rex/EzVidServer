<?php

namespace App\Auth\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Auth\LoginProxy;
use App\Auth\Exceptions\InvalidCredentialsException;
use App\Auth\Requests\LoginRequest;
use App\Http\Controllers\Controller;


class LoginController extends Controller
{
    private $loginProxy;

    private $default_redirect;

    public function __construct(LoginProxy $loginProxy)
    {
        $this->loginProxy = $loginProxy;
        $this->default_redirect = route('vids.index');
    }

    public function show(Request $request) {
        $referer = $request->server('HTTP_REFERER');

        if ($referer === null){
            session()->forget('login_referer');
        }

        if (($referer != route('login')) and !($referer === null)) {
            session()->put('login_referer', $referer);
        }

        if (Auth::check())
            return view('login.already');
        else
            return view('login.show');


    }

    public function login(LoginRequest $request)
    {
        $email = $request->get('email');
        $password = $request->get('password');

        try {
            $session_data = $this->loginProxy->attemptLogin($email, $password);
        } catch (InvalidCredentialsException $e) {
            return back();
        } 

        // Redirect it for now, maybe in the future we will have a dashboard or something
        return redirect(session()->pull('login_referer', $this->default_redirect))
                       ->cookie('access_token',
                                $session_data['access_token'],
                                $session_data['expires_in'] / 60,
                                null,
                                null,
                                false,
                                true); //httpOnly
    }

    public function refresh()
    {
        $session_data = $this->loginProxy->attemptRefresh();

        return redirect()->route('vids.index')
                         ->cookie('access_token',
                                  $session_data['access_token'],
                                  $session_data['expires_in'] / 60,
                                  null,
                                  null,
                                  false,
                                  true); //httpOnly
    }

    public function logout()
    {
        $this->loginProxy->logout();

        return back();
    }
}
