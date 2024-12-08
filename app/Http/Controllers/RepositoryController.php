<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateRepositoryRequest;
use App\Http\Resources\RepositoryResource;
use App\Models\Repository;
use App\Models\Tag;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class RepositoryController extends Controller
{
    /**
     * index
     * 
     * Return all repositories.
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResource
    {
        $repositories = Auth::user()->repositories()->with('tags');

        if ($request->filled('q')) {
            $repositories->whereHas('tags', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->q}%");
            });
        }

        return RepositoryResource::collection($repositories->get());
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
