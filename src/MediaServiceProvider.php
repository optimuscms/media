<?php

namespace Optimus\Media;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Optix\Media\Conversions\Conversion;

class MediaServiceProvider extends ServiceProvider
{
    protected $controllerNamespace = 'Optimus\Media\Http\Controllers';

    public function boot()
    {
        // Config
        $this->publishes([
            __DIR__ . '/../config/media.php' => config_path('media.php')
        ], 'config');

        // Migrations
        $this->publishes([
            __DIR__ . '/../database/migrations/create_media_table.stub' => database_path(
                'migrations/' . date('Y_m_d_His', time()) . '_create_media_table.php'
            )
        ], 'migrations');

        // Routes
        $this->registerApiRoutes();

        Conversion::register('400x300', function ($image) {
            return $image->fit(400, 300);
        });
    }

    protected function registerApiRoutes()
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
