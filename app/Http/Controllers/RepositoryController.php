<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateRepositoryRequest;
use App\Http\Resources\RepositoryResource;
use App\Http\Responses\RestResponse;
use App\Models\Repository;
use App\Models\Tag;
use App\Services\RepositoryService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class RepositoryController extends Controller
{
    public function __construct(
        public RepositoryService $repositoryService,
    ) {}

    /**
     * index
     *
     * Return all repositories.
     * 
     * Example:
     * 
     * GET /api/repositories?q=tag_name
     * 
     * This will return repositories that have the tag `tag_name`.
     * 
     * @return JsonResponse
     */
    public function index(Request $request): JsonResource
    {
        $request->validate([
            'q' => ['nullable', 'string'],
        ]);

        $query = $request->filled('q') ? $request->q : null;

        $repositories = $this->repositoryService->getUserRepositories(Auth::id(), $query);

        return RepositoryResource::collection($repositories);
    }

    /**
     * Details
     *
     * Return the specified repository.
     *
     * @param  $repository  sddsad
     * @return JsonResponse
     */
    public function show(Repository $repository): JsonResource
    {
        return new RepositoryResource($repository->load('tags'));
    }

    /**
     * Update
     *
     * Update tags for the specified repository in storage.
     */
    public function update(UpdateRepositoryRequest $request, Repository $repository): JsonResponse
    {
        $repository->tags()->sync(
            $this->prepareTags($request->tags)
        );

        return response()->json(['message' => 'Update successful']);
    }

    /**
     * Prepare tags for repositories.
     */
    private function prepareTags(array $tags): array
    {
        return collect($tags)->map(function ($tag) {
            return Tag::firstOrCreate(['name' => $tag])->id;
        })->toArray();
    }
}
