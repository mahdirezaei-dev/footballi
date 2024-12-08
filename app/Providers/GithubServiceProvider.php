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
        $this->app->bind('Github', function () {
            return new \App\Modules\Github;
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        AliasLoader::getInstance([
            'Github' => \App\Facades\Github::class,
        ]);
    }
}
