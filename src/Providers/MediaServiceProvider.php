<?php

namespace Optimus\Media\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class MediaServiceProvider extends ServiceProvider
{
    protected $controllerNamespace = 'Optimus\Media\Http\Controllers';

    public function boot()
    {
        // Config
        $this->publishes([
            __DIR__ . '/../../config/media.php' => config_path('media.php')
        ], 'config');

        // Migrations
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        // Routes
        $this->mapApiRoutes();
    }

    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware(['api', 'auth:admin'])
             ->namespace($this->controllerNamespace)
             ->group(function () {
                 Route::apiResource('media', 'MediaController');
                 Route::apiResource('media-folders', 'FoldersController');
             });
    }
}
