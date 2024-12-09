<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Rules\GithubUsername;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct(
        public UserService $userService,
    ) {}

    /**
     * Login
     *
     * Handle a login request to the application.
     */
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'username' => ['required', 'max:255'],
            'password' => ['required', 'min:6'],
        ]);

        $token = $this->userService->login($validated);

        return response()->json($this->formatData($token), 200);
    }

    /**
     * Register
     *
     * Handle a registration request for the application.
     */
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', 'unique:users', new GithubUsername],
            'password' => 'required|string|min:6',
        ]);

        $token = $this->userService->register($validated);

        return response()->json($this->formatData($token), 201);
    }

    /**
     * Format the token array structure.
     *
     * @param  string  $token
     */
    protected function formatData($token): array
    {
        return [
            // 'access_token': The generated access token, which is a JWT (JSON Web Token) used for authenticating the user
            'access_token' => $token,

            // 'token_type': The type of token. Here, 'bearer' indicates the use of Bearer Token protocol for authentication
            'token_type' => 'bearer',

            // 'expires_in': The duration (in seconds) that the access token is valid. This value is calculated by multiplying the TTL (Time To Live) by 60
            'expires_in' => Auth::factory()->getTTL() * 60,
        ];
    }
}
