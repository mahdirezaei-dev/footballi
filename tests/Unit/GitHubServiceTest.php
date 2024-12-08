<?php

namespace Tests\Unit;

use App\Facades\Github;
use Tests\TestCase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class GithubServiceTest extends TestCase
{
    public function test_gets_starred_repositories()
    {
        // Mock the HTTP response
        Http::fake([
            'https://api.github.com/users/testuser/starred' => Http::response([
                ['id' => 1, 'name' => 'Repo1'],
                ['id' => 2, 'name' => 'Repo2']
            ], 200)
        ]);

        // Configure the GitHub service with test data
        config(['github.api_token' => 'test_token']);

        // Create an instance of the GitHub service
        $repositories = Github::getStarredRepositories('testuser');

        // Assert that the result is a Laravel collection
        $this->assertInstanceOf(Collection::class, $repositories);

        // Assert that the collection contains the expected data
        $this->assertCount(2, $repositories);
        $this->assertEquals('Repo1', $repositories->first()['name']);
        $this->assertEquals('Repo2', $repositories->last()['name']);
    }
}
