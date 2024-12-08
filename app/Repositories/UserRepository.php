<?php

namespace App\Repositories;



class UserRepository extends Repository
{
    public function model(): string
    {
        return \App\Models\User::class;
    }
}
