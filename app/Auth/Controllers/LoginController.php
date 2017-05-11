<?php

namespace App\Auth\Controllers;

use Illuminate\Http\Request;
use App\Auth\LoginProxy;
use App\Auth\Requests\LoginRequest;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    private $loginProxy;

    public function __construct(LoginProxy $loginProxy)
    {
        $this->loginProxy = $loginProxy;
    }

    public function show() {
        return view('login.show');
    }

    public function login(LoginRequest $request)
    {
        $email = $request->get('email');
        $password = $request->get('password');

        $session_data = $this->loginProxy->attemptLogin($email, $password);

        // Redirect it for now, maybe in the future we will have a dashboard or something
        return redirect()->route('vids.index')
                         ->cookie('access_token',
                                  $session_data['access_token'],
                                  $session_data['expires_in'],
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
                                  $session_data['expires_in'],
                                  null,
                                  null,
                                  false,
                                  true); //httpOnly
    }

    public function logout()
    {
        $this->loginProxy->logout();

        return redirect()->route('vids.index');
    }
}
