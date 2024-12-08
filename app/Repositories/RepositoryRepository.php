<?php

namespace App\Repositories;


class RepositoryRepository extends Repository
{
    public function model(): string
    {
        return \App\Models\Repository::class;
    }

    public function whereUser($userId)
    {
        return $this->query()->where('user_id', $userId);
    }

    public function searchByTag(string $search)
    {
        return $this->query()->whereHas('tags', function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%");
        });
    }
}
