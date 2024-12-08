<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Rules\GithubUsername;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;


class AuthController extends Controller
{
    public function __construct(
        public UserService $userService,
    ) {}

    /**
     * Login
     * 
     * Handle a login request to the application.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * 
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
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * 
     */
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', 'unique:users', new GithubUsername()],
            'password' => 'required|string|min:6',
        ]);

        $token = $this->userService->register($validated);

        return response()->json($this->formatData($token), 201);
    }

    /**
     * Format the token array structure.
     * 
     * @param string $token
     * @return array
     */
    protected function formatData($token): array
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60,
        ];
    }
}
