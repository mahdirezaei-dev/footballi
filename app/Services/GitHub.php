<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

/**
 * Class GitHub
 *
 * This class provides methods to interact with GitHub API, specifically to fetch the starred repositories of a user.
 */
class GitHub
{
    /** The base URL for GitHub API */
    protected string $baseUrl;

    /** The GitHub API token */
    protected string $token;

    /** The HTTP client used to send requests to the GitHub API */
    protected PendingRequest $httpClient;

    /**
     * GitHub constructor.
     *
     * Initializes the GitHub API service with the necessary configuration.
     */
    public function __construct()
    {
        $this->baseUrl = 'https://api.github.com/';
        $this->token = config('github.api_token');
        $this->httpClient = Http::withToken($this->token);
    }

    /**
     * Get starred repositories of the user.
     *
     * Fetches the starred repositories for the configured user from GitHub.
     * 
     * @param string $username The GitHub username for which to fetch the starred repositories.
     * @return \Illuminate\Support\Collection A collection of starred repositories.
     */
    public function getStarredRepositories(string $username): Collection
    {
        $response = $this->httpClient->get($this->baseUrl . "users/{$username}/starred");

        return collect($response->successful() ? $response->json() : []);
    }
}
