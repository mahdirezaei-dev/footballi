<?php

namespace App\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class GithubServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind('GitHub', function () {
            return new \App\Modules\GitHub;
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        AliasLoader::getInstance([
            'GitHub' => \App\Facades\GitHub::class,
        ]);
    }
}
