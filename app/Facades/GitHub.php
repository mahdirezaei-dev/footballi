<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class GitHub extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'GitHub';
    }
}
