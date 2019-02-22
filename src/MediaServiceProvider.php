<?php

namespace Optimus\Media;

use Optix\Media\ConversionManager;
use Illuminate\Support\ServiceProvider;

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
        $this->registerAdminRoutes();

        // Conversions
        $this->app[ConversionManager::class]
             ->register('400x300', function ($image) {
                 return $image->fit(400, 300);
             });
    }

    protected function registerAdminRoutes()
    {
        $this->app['router']
             ->name('admin.')
             ->prefix('admin')
             ->middleware('web', 'auth:admin')
             ->namespace($this->controllerNamespace)
             ->group(function ($router) {
                 $router->apiResource('media', 'MediaController');
                 $router->apiResource('media-folders', 'FoldersController');
             });
    }
}
