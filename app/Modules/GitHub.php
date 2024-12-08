<?php

namespace App\Modules;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

/**
 * Class Github
 *
 * This class provides methods to interact with Github API, specifically to fetch the starred repositories of a user.
 */
class Github
{
    /** The base URL for Github API */
    protected string $baseUrl;

    /** The Github API token */
    protected string $token;

    /** The HTTP client used to send requests to the Github API */
    protected PendingRequest $httpClient;

    /**
     * Github constructor.
     *
     * Initializes the Github API service with the necessary configuration.
     */
    public function __construct()
    {
        $this->baseUrl = 'https://api.github.com/';
        $this->token = config('github.api_token');
        $this->httpClient = Http::withToken($this->token);
    }

    /**
     * Get the list of repositories starred by a given GitHub user.
     *
     * @param string $username The GitHub username to get the starred repositories for.
     * @return \Illuminate\Support\Collection The collection of starred repositories.
     */
    public function getStarredRepositories(string $username): Collection
    {
        $response = $this->httpClient->get($this->baseUrl . "users/{$username}/starred");
        return collect($response->successful() ? $response->json() : []);
    }


    /**
     * Get the details of a GitHub user by username.
     *
     * @param string $username The GitHub username to get details for.
     * @return \Illuminate\Support\Collection The collection containing user details.
     */
    public function getUser(string $username): Collection
    {
        $response = $this->httpClient->get($this->baseUrl . "users/{$username}");
        return collect($response->successful() ? $response->json() : []);
    }


    /**
     * Check if a given GitHub user exists.
     *
     * @param string $username The GitHub username to check.
     * @return bool True if the user exists, false otherwise.
     */
    public function isUserOnGithub(string $username): bool
    {
        $response = $this->httpClient->get($this->baseUrl . "users/{$username}");
        return $response->successful() ? true : false;
    }
}
