<?php

namespace Optimus\Media\Providers;

use Optix\Media\Facades\Conversion;
use Illuminate\Support\Facades\Route;
use Intervention\Image\Facades\Image;
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

        Conversion::register('media-manager-thumbnail', function (Image $image) {
            $image->fit(400, 300);
        });
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
