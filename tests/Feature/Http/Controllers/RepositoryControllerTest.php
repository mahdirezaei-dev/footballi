<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Repository;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RepositoryControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_user_can_get_repositories()
    {
        // Create a user
        $user = User::factory()->create();

        // Create tags
        $tag1 = Tag::factory()->create(['name' => 'javascript']);
        $tag2 = Tag::factory()->create(['name' => 'java']);
        $tag3 = Tag::factory()->create(['name' => 'php']);

        // Create repositories and assign tags
        $repository1 = Repository::factory()->create(['user_id' => $user->id]);
        $repository2 = Repository::factory()->create(['user_id' => $user->id]);
        $repository3 = Repository::factory()->create(['user_id' => $user->id]);

        $repository1->tags()->attach($tag1);
        $repository2->tags()->attach($tag2);
        $repository3->tags()->attach($tag3);

        // Send GET request to /api/repositories without any filter
        $response = $this->actingAs($user, 'api')->getJson('/api/repositories');

        // Assert that the response is successful
        $response->assertStatus(200)
            ->assertJsonStructure(['data' => [['id', 'url', 'name', 'language', 'description', 'tags']]]);

        // Send GET request to /api/repositories with 'q' filter
        $response = $this->actingAs($user, 'api')->getJson('/api/repositories?q=ja');

        // Assert that the response is successful and the data is correct
        $response->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment([
                'id' => $repository1->id,
                'name' => $repository1->name,
                'description' => $repository1->description,
                'url' => $repository1->url,
                'language' => $repository1->language,
            ])->assertJsonFragment([
                'id' => $repository2->id,
                'name' => $repository2->name,
                'description' => $repository2->description,
                'url' => $repository2->url,
                'language' => $repository2->language,
            ])->assertJsonMissing([
                'id' => $repository3->id,
            ]);
    }
}
