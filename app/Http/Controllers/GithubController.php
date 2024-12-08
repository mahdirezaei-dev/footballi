<?php

namespace App\Http\Controllers;

use App\Facades\Github;
use App\Http\Responses\RestResponse;
use App\Models\Repository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class GithubController
{
    protected $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }

    /**
     * Sync
     *
     * Sync GitHub starred repositories with the local database.
     *
     * @return JsonResponse Returns a JSON response indicating that the repositories have been successfully synced.
     */
    public function sync(): JsonResponse
    {

        $repositories = Github::getStarredRepositories($this->user->username)
            ->select(['id', 'name', 'description', 'url', 'language']);

        // TODO: foreach / try-catch , trasaction ...
        $this->deleteUnstarredRepositories($repositories);

        $this->user->repositories()
            ->upsert(
                $repositories->toArray(),
                ['id', 'user_id'],
                ['name', 'description', 'url', 'language']
            );

        return RestResponse::success(null, 'Repositories synced with Github!', 201);
    }

    /**
     * Delete local repositories that are no longer starred on GitHub.
     */
    private function deleteUnstarredRepositories($repositories): void
    {
        Repository::destroy(array_diff(
            $this->user->repositories()->pluck('id')->toArray(),
            $repositories->pluck('id')->toArray()
        ));
    }
}
