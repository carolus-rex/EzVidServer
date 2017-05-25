<?php

namespace App\Auth\Services;

use Components\Users\Repositories\UserRepository;

class RegisterService
{
	private $userRepository;

	public function __construct(UserRepository $userRepository)
	{
		$this->userRepository = $userRepository;
	}

	public function register($request)
	{
		$this->userRepository->create($request->all());
	}
}
