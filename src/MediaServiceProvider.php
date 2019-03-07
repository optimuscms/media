<?php

namespace Optimus\Media;

use Optix\Media\ConversionManager;
use Illuminate\Support\ServiceProvider;

class MediaServiceProvider extends ServiceProvider
{
    protected $controllerNamespace = 'Optimus\Media\Http\Controllers';

    public function boot()
    {
        // Migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Config
        $this->publishes([
            __DIR__ . '/../config/media.php' => config_path('media.php')
        ], 'config');

        // Routes
        $this->registerAdminRoutes();

        // Conversions
        $this->app[ConversionManager::class]
             ->register('media-thumbnail', function ($image) {
                 return $image->fit(400, 300);
             });
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/media.php', 'media'
        );
    }

    protected function registerAdminRoutes()
    {
        $this->app['router']
             ->name('admin.api.')
             ->prefix('admin/api')
             ->middleware('web', 'auth:admin')
             ->namespace($this->controllerNamespace)
             ->group(function ($router) {
                 $router->apiResource('media', 'MediaController');
                 $router->apiResource('media-folders', 'FoldersController');
             });
    }
}
