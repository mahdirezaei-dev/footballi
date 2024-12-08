<?php

namespace App\Services;

use App\Repositories\RepositoryRepository;
use Illuminate\Support\Collection;

class RepositoryService
{
    protected $repositoryRepository;

    public function __construct(RepositoryRepository $repositoryRespository)
    {
        $this->repositoryRepository = $repositoryRespository;
    }


    public function getUserRepositories($userId, $search = null): Collection
    {
        $repositories = $this->repositoryRepository->whereUser($userId)->with('tags');

        if ($search) {
            $repositories = $this->repositoryRepository->searchByTag($search);
        }

        return $repositories->get();
    }
}
