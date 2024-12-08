<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;

class UserService
{
    public function __construct(protected UserRepository $userRepository) {}

    public function register(array $data)
    {
        $data['password'] = bcrypt($data['password']);

        $user = $this->userRepository->create($data);

        if (!$user) {
            throw new \Exception('Failed to create user.');
        }

        return Auth::login($user);
    }

    public function login(array $data)
    {
        if (! $token = Auth::attempt($data)) {
            throw new AuthenticationException(__('auth.failed'));
        }

        return $token;
    }
}
