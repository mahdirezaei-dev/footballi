<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateRepositoryRequest;
use App\Http\Resources\RepositoryResource;
use App\Http\Responses\RestResponse;
use App\Models\Repository;
use App\Models\Tag;
use App\Services\RepositoryService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
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
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $query = $request->filled('q') ? $request->q : null;

        $repositories = $this->repositoryService->getUserRepositories(Auth::id(), $query);

        $data = RepositoryResource::collection($repositories);

        return RestResponse::success(data: $data, code: 200);
    }

    /**
     * Details
     * 
     * Return the specified repository.
     *
     * @param $repository sddsad
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
     *
     * @param UpdateRepositoryRequest $request
     * @param Repository $repository
     * @return JsonResponse
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
