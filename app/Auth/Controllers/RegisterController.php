<?php

namespace App\Auth\Controllers;

use App\Http\Controllers\Controller;

use App\Auth\Requests\RegisterRequest;
use App\Auth\Services\RegisterService;

use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
	private $registerService;

	public function __construct(RegisterService $registerService)
	{
		$this->registerService = $registerService;
	}

	public function show()
	{
		if (Auth::check())
            return view('login.already');
        else
			return view('register.show');
	}

	public function register(RegisterRequest $request)
	{
		$this->registerService->register($request);
		return view('register.successfull');
	}
}
